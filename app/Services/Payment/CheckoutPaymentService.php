<?php

namespace App\Services\Payment;

use App\DTOs\Payment\PaymentRequestDto;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Events\OrderPaymentRequestEvent;
use App\Models\Order;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Delivery\DeliveryRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Order\Data\OrderDraft;
use App\Services\Order\OrderFactoryInterface;
use App\Services\Order\OrderPaymentFinalizerInterface;
use App\Services\Payment\Concerns\BuildsPaymentResponse;
use App\Services\Payment\Data\CheckoutContext;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * ثبت سفارش جدید از روی سبد خرید.
 *
 * سه سناریو دارد و تفاوت‌شان عمداً در سه سازنده‌ی OrderDraft کنار هم دیده می‌شود،
 * نه لای آرگومان‌های بی‌نام:
 *   - placeGatewayOrder      : کل مبلغ از درگاه
 *   - placeWalletOrder       : کل مبلغ از کیف پول
 *   - placePartialWalletOrder: کیف پول + درگاه
 */
readonly class CheckoutPaymentService
{
    use BuildsPaymentResponse;

    public function __construct(
        private CartRepositoryInterface        $cartRepository,
        private CartItemRepositoryInterface    $cartItemRepository,
        private UserRepositoryInterface        $userRepository,
        private DeliveryRepositoryInterface    $deliveryRepository,
        private AddressRepositoryInterface     $addressRepository,
        private CheckoutServiceInterface       $checkoutService,
        private CartItemServiceInterface       $cartItemService,
        private OrderFactoryInterface          $orderFactory,
        private OrderPaymentFinalizerInterface $orderPaymentFinalizer,
        private PaymentGatewayRouter           $gatewayRouter,
        private CouponCalculator               $couponCalculator,
    )
    {
    }

    public function request(PaymentRequestDto $dto)
    {
        $userId = $dto->userId;
        $useWallet = $dto->wallet;
        $shippingMethod = $dto->shippingMethod;
        $shippingPrice = $dto->shippingPrice;

        $context = $this->buildContext($userId, $shippingMethod, $dto->code, $shippingPrice, $dto->gateway);

        if (!$useWallet) {
            return $this->placeGatewayOrder($context, $shippingMethod, $shippingPrice);
        }

        if ($context->payableAmount <= $context->user->wallet) {
            return $this->placeWalletOrder($context);
        }

        return $this->placePartialWalletOrder($context);
    }

    /**
     * ثبت سفارش با پرداخت کاملِ کیف پول، بدون عبور از صفحه‌ی انتخاب درگاه.
     */
    public function requestByWallet($userId)
    {
        $context = $this->buildContext($userId, null, null, null, PaymentGateway::Wallet);

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
            return $this->hold($order);
        }

        $this->orderPaymentFinalizer->markPaid($order, $context->payableAmount, null, $context->user);

        return $this->paidRedirect();
    }

    /**
     * مقدمه‌ی مشترک هر سناریو: اعتبارسنجی سبد، قیمت‌ها و کوپن.
     *
     * @param  mixed  $shippingMethod  null یعنی روش ارسالِ ثبت‌شده روی سبد ملاک است
     * @param  mixed  $shippingPrice   null یعنی قیمتِ خودِ روش ارسال ملاک است
     */
    private function buildContext($userId, $shippingMethod, $code, $shippingPrice, $gateway): CheckoutContext
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
            return $this->hold($order);
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
            return $this->hold($order);
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
            return $this->hold($order);
        }

        event(new OrderPaymentRequestEvent($order));

        // این مسیر همیشه از درگاه بانکی پیش‌فرض استفاده می‌کند و $gateway را نادیده می‌گیرد
        return $this->paymentRedirect(
            $this->gatewayRouter->request(PaymentGateway::Online, $order, $remaining)
        );
    }

    private function initialStatus(CheckoutContext $context): OrderStatus
    {
        return $context->hasLimitedItem ? OrderStatus::OnHold : OrderStatus::Unpaid;
    }

    private function hold(Order $order): array
    {
        $this->orderFactory->holdForApproval($order);

        return $this->limitRedirect();
    }
}
