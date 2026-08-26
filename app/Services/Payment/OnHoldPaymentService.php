<?php

namespace App\Services\Payment;

use App\Enums\OnHoldOrderStatus;
use App\Enums\PaymentGateway;
use App\Events\OrderPaymentRequestEvent;
use App\Exceptions\BreakException;
use App\Models\Order;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\CouponUser\CouponUserRepositoryInterface;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderInfo\OrderInfoRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Checkout\ShippingMethodResolver;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Order\OrderPaymentFinalizerInterface;
use App\Services\Payment\Concerns\BuildsPaymentResponse;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * پرداخت سفارش‌های معلقی که مدیر تاییدشان کرده است.
 *
 * برخلاف CheckoutPaymentService اینجا سفارش از قبل وجود دارد و اقلامش قفل است؛
 * فقط شرایط پرداخت (آدرس، ارسال، کوپن، کیف پول، درگاه) دوباره اعمال می‌شود.
 */
readonly class OnHoldPaymentService
{
    use BuildsPaymentResponse;

    public function __construct(
        private OnHoldOrderRepositoryInterface $onHoldOrderRepository,
        private OrderRepositoryInterface       $orderRepository,
        private OrderItemRepositoryInterface   $orderItemRepository,
        private OrderInfoRepositoryInterface   $orderInfoRepository,
        private CouponUserRepositoryInterface  $couponUserRepository,
        private CartRepositoryInterface        $cartRepository,
        private CartItemRepositoryInterface    $cartItemRepository,
        private UserRepositoryInterface        $userRepository,
        private AddressRepositoryInterface     $addressRepository,
        private CheckoutServiceInterface       $checkoutService,
        private CartItemServiceInterface       $cartItemService,
        private OnHoldOrderServiceInterface    $onHoldOrderService,
        private ShippingMethodResolver         $shippingMethodResolver,
        private OrderPaymentFinalizerInterface $orderPaymentFinalizer,
        private PaymentGatewayRouter           $gatewayRouter,
        private CouponCalculator               $couponCalculator,
    )
    {
    }

    /**
     * هدایت به درگاه با همان مبلغی که هنگام ثبت سفارش قفل شده بود.
     */
    public function request($id, $userId)
    {
        $order = $this->payableOrder($id, $userId);

        // این مسیر فقط دیجی‌پی را جدا می‌کند؛ بقیه (از جمله اسنپ‌پی) به درگاه بانکی می‌روند
        $gateway = PaymentGateway::normalize($order->payment_method) === PaymentGateway::DigiPay
            ? PaymentGateway::DigiPay
            : PaymentGateway::Online;

        return $this->gatewayRouter->request($gateway, $order, $order->final_price, $order->orderInfo->mobile);
    }

    public function payByWallet($id, $userId)
    {
        $order = $this->payableOrder($id, $userId);

        $finalPrice = $order->final_price;
        $user = $this->userRepository->findOrFail($userId);
        if ($finalPrice > $user->wallet) {
            throw new BadRequestHttpException("موجودی کیف پول شما برای ثبت این سفارش کافی نیست !");
        }

        $this->orderPaymentFinalizer->markPaid($order, $finalPrice, null, $user);

        return $this->paidRedirect();
    }

    /**
     * پرداخت از صفحه‌ی چک‌اوت اختصاصیِ سفارش معلق.
     *
     * آدرس، روش ارسال، کد تخفیف، کیف پول و درگاه دوباره اعمال و روی همان سفارش
     * ذخیره می‌شوند. قیمت اقلام از مقادیر فریزشده‌ی order_item خوانده می‌شود تا
     * مبلغِ تاییدشده تغییر نکند.
     */
    public function checkoutPayment($id, $userId, $useWallet, $shippingMethod, $code = null, $gateway = 1)
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
        $address = $this->activeAddress($userId);

        $gatewayEnum = PaymentGateway::normalize($gateway);
        // دیجی‌پی و اسنپ‌پی با کیف پول ترکیب نمی‌شوند
        if ($gatewayEnum?->isCreditProvider()) {
            $useWallet = false;
        }

        $prices = $this->frozenItemsPrice($orderItems);
        $totalItemsPrice = $prices["totalItemPrice"];
        $shippingPrice = $this->resolveShippingPrice($orderItems, $address, $totalItemsPrice, $shippingMethod);

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
        $this->replaceCoupon($order, $userId, $coupon->coupon);

        if ($useWallet && $finalPrice <= $user->wallet) {
            return $this->settleWithWallet($order, $user, $totalItemsPrice, $shippingPrice, $shippingMethod, $off, $finalPrice);
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
     * کل مبلغ با موجودی کیف پول پوشش داده می‌شود.
     */
    private function settleWithWallet(Order $order, $user, $totalItemsPrice, $shippingPrice, $shippingMethod, $off, $finalPrice): array
    {
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

    private function activeAddress($userId)
    {
        $address = $this->addressRepository->findActiveByUserId($userId);
        if (!$address || !$address->city_id || !$address->province_id || !$address->mobile || !$address->address) {
            throw new BreakException(\Lang::get("exceptions.address_not_find"));
        }
        return $address;
    }

    /**
     * هزینه‌ی ارسال سمت سرور دوباره حساب می‌شود؛ مقدار ارسالی کلاینت ملاک نیست.
     */
    private function resolveShippingPrice($orderItems, $address, $totalItemsPrice, $shippingMethod): int
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
    private function replaceCoupon(Order $order, $userId, $coupon): void
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
     * شرط‌های قدیمیِ پرداخت سفارش معلق، برای request() و payByWallet().
     *
     * عمداً از OnHoldOrderService::assertPayable استفاده نمی‌کند: آن نسخه وضعیت خودِ
     * سفارش را هم بررسی می‌کند و سخت‌گیرتر است. یکسان‌سازی این دو رفتارِ این اندپوینت‌ها
     * را عوض می‌کند و باید جداگانه تصمیم گرفته شود.
     */
    private function payableOrder($id, $userId): Order
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
    private function frozenItemsPrice($orderItems): array
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
}
