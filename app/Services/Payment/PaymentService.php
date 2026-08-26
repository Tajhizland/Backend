<?php

namespace App\Services\Payment;

use App\Enums\OnHoldOrderStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Events\OrderPaidEvent;
use App\Events\OrderPaymentRequestEvent;
use App\Exceptions\BreakException;
use App\Models\Order;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\CouponUser\CouponUserRepositoryInterface;
use App\Repositories\Delivery\DeliveryRepositoryInterface;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderInfo\OrderInfoRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Checkout\ShippingMethodResolver;
use App\Services\DigiPay\DigiPayService;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Order\Data\OrderDraft;
use App\Services\Order\OrderFactoryInterface;
use App\Services\Order\OrderPaymentFinalizerInterface;
use App\Services\Payment\Data\CheckoutContext;
use App\Services\SnappPay\SnappPayService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * هماهنگ‌کننده‌ی جریان پرداخت.
 *
 * کارهای تکراری به همکارهای اختصاصی سپرده شده‌اند:
 *  - OrderFactory            : ساخت سفارش از روی سبد
 *  - OrderPaymentFinalizer   : نهایی‌سازی سفارشِ پرداخت‌شده
 *  - PaymentGatewayRouter    : انتخاب درگاه
 *  - CouponCalculator        : محاسبه‌ی تخفیف
 */
class PaymentService implements PaymentServicesInterface
{
    private const THANK_YOU_PAGE = "/thank_you_page";

    public function __construct(
        private CartRepositoryInterface         $cartRepository,
        private CartItemRepositoryInterface     $cartItemRepository,
        private UserRepositoryInterface         $userRepository,
        private DeliveryRepositoryInterface     $deliveryRepository,
        private OrderRepositoryInterface        $orderRepository,
        private OrderItemRepositoryInterface    $orderItemRepository,
        private OrderInfoRepositoryInterface    $orderInfoRepository,
        private AddressRepositoryInterface      $addressRepository,
        private CartItemServiceInterface        $cartItemService,
        private OnHoldOrderRepositoryInterface  $onHoldOrderRepository,
        private CheckoutServiceInterface        $checkoutService,
        private CouponUserRepositoryInterface   $couponUserRepository,
        private DigiPayService                  $digiPayService,
        private SnappPayService                 $snappPayService,
        private OnHoldOrderServiceInterface     $onHoldOrderService,
        private ShippingMethodResolver          $shippingMethodResolver,
        private OrderFactoryInterface           $orderFactory,
        private OrderPaymentFinalizerInterface  $orderPaymentFinalizer,
        private PaymentGatewayRouter            $gatewayRouter,
        private CouponCalculator                $couponCalculator,
    )
    {
    }

    // ---------------------------------------------------------------- سفارش جدید

    public function request($userId, $useWallet, $shippingMethod, $code = null, $shippingPrice = 0, $gateway = 1)
    {
        $context = $this->buildCheckoutContext($userId, $shippingMethod, $code, $shippingPrice, $gateway);

        if (!$useWallet) {
            return $this->placeGatewayOrder($context, $shippingMethod, $shippingPrice);
        }

        if ($context->payableAmount <= $context->user->wallet) {
            return $this->placeWalletOrder($context);
        }

        return $this->placePartialWalletOrder($context);
    }

    /**
     * مقدمه‌ی مشترک هر سه سناریو: اعتبارسنجی سبد، قیمت‌ها و کوپن.
     */
    /**
     * @param  mixed  $shippingMethod  null یعنی روش ارسالِ ثبت‌شده روی سبد ملاک است
     * @param  mixed  $shippingPrice   null یعنی قیمتِ خودِ روش ارسال ملاک است
     */
    private function buildCheckoutContext($userId, $shippingMethod = null, $code = null, $shippingPrice = null, $gateway = null): CheckoutContext
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkoutService->finalCheckout($cart, $cartItems);

        $hasLimitedItem = $this->cartItemService->checkLimit($cartItems);
        $user = $this->userRepository->findOrFail($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        $delivery = $this->deliveryRepository->findOrFail($shippingMethod ?? $cart->delivery_method);
        $shippingPrice ??= $delivery->price;

        $isDigipay = PaymentGateway::normalize($gateway) === PaymentGateway::DigiPay;
        $cartPrices = $this->cartItemService->calculatePrice($cartItems, $isDigipay);

        $itemsPrice = $cartPrices["totalItemPrice"];
        $amount = $itemsPrice + $shippingPrice;
        $extraAmount = $cartPrices["extraPrice"] + $shippingPrice;

        $coupon = $this->couponCalculator->apply($code, $userId, $amount, $extraAmount);

        return new CheckoutContext(
            user: $user,
            cart: $cart,
            cartItems: $cartItems,
            address: $address,
            delivery: $delivery,
            hasLimitedItem: $hasLimitedItem,
            gateway: $gateway,
            itemsPrice: $itemsPrice,
            payableAmount: $amount - $coupon->off,
            payableExtra: $extraAmount - $coupon->extraOff,
            maxDeliveryDelay: $cartPrices["maxDeliveryDelay"],
            coupon: $coupon,
        );
    }

    /**
     * پرداخت کامل از درگاه، بدون دخالت کیف پول.
     *
     * نکته‌ی تاریخی: این سناریو هزینه‌ی ارسال و روش ارسالِ ارسالی از کلاینت را ملاک
     * می‌گیرد، در حالی که دو سناریوی کیف‌پولی از cart->delivery_method و delivery->price
     * استفاده می‌کنند.
     */
    private function placeGatewayOrder(CheckoutContext $context, $shippingMethod, $shippingPrice): array
    {
        $order = $this->orderFactory->createFromCart(new OrderDraft(
            user: $context->user,
            cart: $context->cart,
            cartItems: $context->cartItems,
            address: $context->address,
            status: $this->initialStatus($context),
            paymentMethod: $context->gateway,
            deliveryMethod: $shippingMethod,
            itemsPrice: $context->itemsPrice,
            deliveryPrice: $shippingPrice,
            totalPrice: $context->payableAmount,
            useWalletPrice: 0,
            finalPrice: $context->payableAmount,
            off: $context->coupon->off,
            deliveryDelayDays: $context->maxDeliveryDelay,
            coupon: $context->coupon->coupon,
            disableDiscount: $context->isDigipay(),
        ));

        if ($context->hasLimitedItem) {
            return $this->holdForApproval($order);
        }

        event(new OrderPaymentRequestEvent($order));

        // دیجی‌پی مبلغِ بدون تخفیفِ محصول به‌علاوه‌ی کارمزد را می‌گیرد
        $amount = $context->isDigipay() ? $context->payableExtra : $context->payableAmount;

        return $this->paymentRedirect(
            $this->gatewayRouter->request($context->gateway, $order, $amount, $context->address->mobile)
        );
    }

    /**
     * کل مبلغ از کیف پول پوشش داده می‌شود؛ سفارش بلافاصله پرداخت‌شده ثبت می‌گردد.
     */
    private function placeWalletOrder(CheckoutContext $context): array
    {
        $order = $this->orderFactory->createFromCart(new OrderDraft(
            user: $context->user,
            cart: $context->cart,
            cartItems: $context->cartItems,
            address: $context->address,
            status: $this->initialStatus($context),
            paymentMethod: PaymentGateway::Wallet,
            deliveryMethod: $context->cart->delivery_method,
            itemsPrice: $context->itemsPrice,
            deliveryPrice: $context->delivery->price,
            totalPrice: $context->payableAmount,
            useWalletPrice: $context->payableAmount,
            finalPrice: 0,
            off: $context->coupon->off,
            deliveryDelayDays: $context->maxDeliveryDelay,
            coupon: $context->coupon->coupon,
        ));

        if ($context->hasLimitedItem) {
            return $this->holdForApproval($order);
        }

        $this->orderPaymentFinalizer->markPaid($order, $context->payableAmount, null, $context->user);

        return $this->paidRedirect();
    }

    /**
     * کیف پول کفاف نمی‌دهد؛ باقی‌مانده از درگاه بانکی گرفته می‌شود.
     */
    private function placePartialWalletOrder(CheckoutContext $context): array
    {
        $remaining = $context->payableAmount - $context->user->wallet;

        $order = $this->orderFactory->createFromCart(new OrderDraft(
            user: $context->user,
            cart: $context->cart,
            cartItems: $context->cartItems,
            address: $context->address,
            status: $this->initialStatus($context),
            paymentMethod: $context->cart->payment_method,
            deliveryMethod: $context->cart->delivery_method,
            itemsPrice: $context->itemsPrice,
            deliveryPrice: $context->delivery->price,
            totalPrice: $context->payableAmount,
            useWalletPrice: $context->user->wallet,
            finalPrice: $remaining,
            off: $context->coupon->off,
            deliveryDelayDays: $context->maxDeliveryDelay,
            coupon: $context->coupon->coupon,
        ));

        if ($context->hasLimitedItem) {
            return $this->holdForApproval($order);
        }

        event(new OrderPaymentRequestEvent($order));

        // این مسیر همیشه از درگاه بانکی پیش‌فرض استفاده می‌کند و $gateway را نادیده می‌گیرد
        return $this->paymentRedirect(
            $this->gatewayRouter->request(PaymentGateway::Online, $order, $remaining)
        );
    }

    public function verifyOrderByWallet($userId)
    {
        $context = $this->buildCheckoutContext($userId, null, null, 0, PaymentGateway::Wallet);

        if ($context->payableAmount > $context->user->wallet) {
            throw new BadRequestHttpException("موجودی کیف پول شما برای ثبت این سفارش کافی نیست !");
        }

        $order = $this->orderFactory->createFromCart(new OrderDraft(
            user: $context->user,
            cart: $context->cart,
            cartItems: $context->cartItems,
            address: $context->address,
            status: $this->initialStatus($context),
            paymentMethod: PaymentGateway::Wallet,
            deliveryMethod: $context->cart->delivery_method,
            itemsPrice: $context->itemsPrice,
            deliveryPrice: $context->delivery->price,
            totalPrice: 0,
            useWalletPrice: 0,
            finalPrice: $context->payableAmount,
            deliveryDelayDays: $context->maxDeliveryDelay,
        ));

        if ($context->hasLimitedItem) {
            return $this->holdForApproval($order);
        }

        $this->orderPaymentFinalizer->markPaid($order, $context->payableAmount, null, $context->user);

        return $this->paidRedirect();
    }

    // ------------------------------------------------------------ سفارش معلق

    public function onHoldOrderRequest($id, $userId)
    {
        $order = $this->legacyPayableOnHoldOrder($id, $userId);

        // این مسیر فقط دیجی‌پی را جدا می‌کند؛ بقیه (از جمله اسنپ‌پی) به درگاه بانکی می‌روند
        $gateway = PaymentGateway::normalize($order->payment_method) === PaymentGateway::DigiPay
            ? PaymentGateway::DigiPay
            : PaymentGateway::Online;

        return $this->gatewayRouter->request($gateway, $order, $order->final_price, $order->orderInfo->mobile);
    }

    public function onHoldOrderVerifyByWallet($id, $userId)
    {
        $order = $this->legacyPayableOnHoldOrder($id, $userId);

        $finalPrice = $order->final_price;
        $user = $this->userRepository->findOrFail($userId);
        if ($finalPrice > $user->wallet) {
            throw new BadRequestHttpException("موجودی کیف پول شما برای ثبت این سفارش کافی نیست !");
        }

        $this->orderPaymentFinalizer->markPaid($order, $finalPrice, null, $user);

        return $this->paidRedirect();
    }

    /**
     * پرداخت یک سفارش معلقِ تاییدشده از صفحه‌ی چک‌اوت اختصاصی‌اش.
     *
     * برخلاف request() اینجا سفارش از قبل وجود دارد و اقلامش قفل است؛ فقط آدرس،
     * روش ارسال، کد تخفیف، کیف پول و درگاه دوباره اعمال و روی همان سفارش ذخیره می‌شوند.
     * قیمت اقلام از مقادیر فریزشده‌ی order_item خوانده می‌شود تا مبلغِ تاییدشده تغییر نکند.
     */
    public function onHoldOrderCheckoutPayment($id, $userId, $useWallet, $shippingMethod, $code = null, $gateway = 1)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        $this->onHoldOrderService->assertPayable($onHoldOrder, $userId);

        $order = $this->orderRepository->findOrFail($onHoldOrder->order_id);
        $orderItems = $this->orderItemRepository->getByOrderId($order->id);
        if ($orderItems->isEmpty()) {
            throw new BreakException(\Lang::get("exceptions.unavailable_product_in_cart"));
        }
        // همان اعتبارسنجی چک‌اوت (فعال بودن محصول/رنگ و کافی بودن موجودی)، اما روی اقلام سفارش
        $this->cartItemService->checkAllow($orderItems);

        $user = $this->userRepository->findOrFail($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        if (!$address || !$address->city_id || !$address->province_id || !$address->mobile || !$address->address) {
            throw new BreakException(\Lang::get("exceptions.address_not_find"));
        }

        $gatewayEnum = PaymentGateway::normalize($gateway);
        // دیجی‌پی و اسنپ‌پی با کیف پول ترکیب نمی‌شوند
        if ($gatewayEnum?->isCreditProvider()) {
            $useWallet = false;
        }

        $prices = $this->onHoldOrderItemsPrice($orderItems);
        $totalItemsPrice = $prices["totalItemPrice"];

        $shippingPrice = $this->resolveOnHoldShippingPrice($orderItems, $address, $totalItemsPrice, $shippingMethod);

        $coupon = $this->couponCalculator->apply(
            $code,
            $userId,
            $totalItemsPrice + $shippingPrice,
            $prices["extraPrice"] + $shippingPrice,
            $totalItemsPrice,
        );

        $finalPrice = max(0, (int)round($totalItemsPrice + $shippingPrice - $coupon->off));
        $finalExtraPrice = max(0, (int)round($prices["extraPrice"] + $shippingPrice - $coupon->extraOff));
        $off = (int)round($coupon->off);

        $this->refreshOrderInfo($order, $user, $address);
        $this->replaceOrderCoupon($order, $userId, $coupon->coupon);

        // کل مبلغ با موجودی کیف پول پوشش داده می‌شود
        if ($useWallet && $finalPrice <= $user->wallet) {
            $this->orderRepository->update($order, [
                "price" => $totalItemsPrice,
                "delivery_price" => $shippingPrice,
                "delivery_method" => $shippingMethod,
                "payment_method" => PaymentGateway::Wallet->value,
                "off" => $off,
                "total_price" => $finalPrice,
                "use_wallet_price" => $finalPrice,
                "final_price" => 0,
            ]);

            $this->orderPaymentFinalizer->markPaid($order, $finalPrice, null, $user);

            return $this->paidRedirect();
        }

        // مبلغی که در نهایت از درگاه گرفته می‌شود (دیجی‌پی مبلغ بدون تخفیف + کارمزد را می‌گیرد)
        $chargeAmount = $gatewayEnum?->usesExtraPrice() ? $finalExtraPrice : $finalPrice;
        $useWalletPrice = $useWallet ? min($user->wallet, $chargeAmount) : 0;
        $payablePrice = $chargeAmount - $useWalletPrice;

        $this->orderRepository->update($order, [
            "price" => $totalItemsPrice,
            "delivery_price" => $shippingPrice,
            "delivery_method" => $shippingMethod,
            "payment_method" => PaymentGateway::toValue($gateway),
            "off" => $off,
            "total_price" => $chargeAmount,
            "use_wallet_price" => $useWalletPrice,
            "final_price" => $payablePrice,
        ]);
        event(new OrderPaymentRequestEvent($order));

        return $this->paymentRedirect(
            $this->gatewayRouter->request($gateway, $order, $payablePrice, $address->mobile, $orderItems)
        );
    }

    /**
     * هزینه‌ی ارسال سمت سرور دوباره حساب می‌شود؛ مقدار ارسالی کلاینت ملاک نیست.
     */
    private function resolveOnHoldShippingPrice($orderItems, $address, $totalItemsPrice, $shippingMethod): int
    {
        foreach ($this->shippingMethodResolver->resolve($orderItems, $address, $totalItemsPrice) as $item) {
            if ($item->id == $shippingMethod) {
                return max(0, (int)$item->price);
            }
        }

        throw new BreakException(\Lang::get("exceptions.delivery_not_find"));
    }

    /**
     * اطلاعات گیرنده با آدرس فعالِ فعلی به‌روز می‌شود.
     */
    private function refreshOrderInfo(Order $order, $user, $address): void
    {
        $orderInfo = $this->orderInfoRepository->findOrFail($order->order_info_id);
        $this->orderInfoRepository->update($orderInfo, [
            "name" => $user->name,
            "last_name" => $user->last_name,
            "national_code" => $user->national_code,
            "mobile" => $address->mobile,
            "tell" => $address->tell,
            "province_id" => $address->province_id,
            "city_id" => $address->city_id,
            "address" => $address->address,
            "zip_code" => $address->zip_code,
        ]);
    }

    /**
     * کد تخفیفِ تلاش قبلی (اگر بوده) جای خود را به انتخاب فعلی می‌دهد.
     */
    private function replaceOrderCoupon(Order $order, $userId, $coupon): void
    {
        $this->couponUserRepository->deleteByOrderId($order->id);
        if ($coupon) {
            $this->couponUserRepository->create([
                "order_id" => $order->id,
                "user_id" => $userId,
                "coupon_id" => $coupon->id,
            ]);
        }
    }

    /**
     * شرط‌های قدیمیِ پرداخت سفارش معلق.
     *
     * عمداً از OnHoldOrderService::assertPayable استفاده نمی‌کند: آن نسخه وضعیت خودِ
     * سفارش را هم بررسی می‌کند و سخت‌گیرتر است. یکسان‌سازی این دو رفتار این دو
     * اندپوینت را عوض می‌کند و باید جداگانه تصمیم گرفته شود.
     */
    private function legacyPayableOnHoldOrder($id, $userId): Order
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);

        if (Carbon::parse($onHoldOrder->expire_date) < Carbon::now()) {
            throw new BreakException(\Lang::get("exceptions.expired_order"));
        }
        if ($onHoldOrder->status != OnHoldOrderStatus::Accept->value) {
            throw new BreakException(\Lang::get("exceptions.reject_order"));
        }

        $cart = $this->cartRepository->getCartByOrderId($onHoldOrder->order_id);
        if ($cart->user_id != $userId) {
            throw new BreakException(\Lang::get("exceptions.not_your_order"));
        }

        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkoutService->finalCheckout($cart, $cartItems);

        return $this->orderRepository->findOrFail($onHoldOrder->order_id);
    }

    /**
     * مبلغ اقلام یک سفارش از روی قیمت‌های فریزشده‌ی order_item.
     *
     * - totalItemPrice : مبلغ عادی (final_price هر واحد شامل تخفیف و قیمت گارانتی است)
     * - extraPrice     : مبلغ دیجی‌پی؛ تخفیف محصول اعمال نمی‌شود و درصد کارمزد اضافه می‌گردد
     */
    private function onHoldOrderItemsPrice($orderItems): array
    {
        $totalItemPrice = 0;
        $extraPrice = 0;
        foreach ($orderItems as $item) {
            $totalItemPrice += $item->final_price * $item->count;

            $withoutDiscount = ($item->final_price + $item->discount) * $item->count;
            $percent = $item->product?->digipay_extra_price ?? 0;
            $extraPrice += round($withoutDiscount + ($withoutDiscount * $percent / 100));
        }
        return [
            "totalItemPrice" => $totalItemPrice,
            "extraPrice" => $extraPrice,
        ];
    }

    // ------------------------------------------------------------- بازگشت از درگاه

    public function verifyPayment($request)
    {
        $callback = $this->gatewayRouter->onlineGateway()->callbackParams($request);
        $this->gatewayRouter->onlineGateway()->verify($callback->trackId);

        return $this->completeVerifiedPayment($callback->orderId, $callback->trackId);
    }

    public function verifyPayment2($request)
    {
        $callback = $this->digiPayService->callbackParams($request);
        $this->digiPayService->verify($callback->trackId, $callback->orderId);

        return $this->completeVerifiedPayment($callback->orderId, $callback->trackId);
    }

    public function verifyPaymentSnapppay($request)
    {
        try {
            DB::beginTransaction();

            $callback = $this->snappPayService->callbackParams($request);
            $order = $this->orderRepository->findOrFail($callback->orderId);

            $verify = $this->snappPayService->verify($order->payment_token);
            if ($verify["successful"] != true) {
                throw new BreakException("پرداخت ناموفق بود");
            }

            $this->snappPayService->settle($order->payment_token);

            // اسنپ‌پی کل مبلغ را خودش تسویه می‌کند، پس چیزی از کیف پول کم نمی‌شود
            $this->orderPaymentFinalizer->applyPayment($order, 0, $verify["response"]["transactionId"]);

            DB::commit();
            event(new OrderPaidEvent($order));

            return $callback->orderId;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * مشترکِ بازگشت موفق از زیبال و دیجی‌پی.
     */
    private function completeVerifiedPayment($orderId, $trackId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        DB::transaction(function () use ($order, $trackId) {
            $this->orderPaymentFinalizer->applyPayment($order, $order->use_wallet_price, $trackId);
        });

        event(new OrderPaidEvent($order));

        return 1;
    }

    public function snappPayEligible($amount)
    {
        // مبلغ از فرانت به تومان می‌آید و اسنپ‌پی مبلغ را به ریال می‌خواهد (× ۱۰)
        return $this->snappPayService->eligible($amount * 10);
    }

    // ----------------------------------------------------------------- کمکی‌ها

    private function initialStatus(CheckoutContext $context): OrderStatus
    {
        return $context->hasLimitedItem ? OrderStatus::OnHold : OrderStatus::Unpaid;
    }

    private function holdForApproval(Order $order): array
    {
        $this->orderFactory->holdForApproval($order);

        return ["path" => self::THANK_YOU_PAGE, "type" => "limit"];
    }

    private function paidRedirect(): array
    {
        return ["path" => self::THANK_YOU_PAGE, "type" => "paid"];
    }

    private function paymentRedirect($path): array
    {
        return ["path" => $path, "type" => "payment"];
    }
}
