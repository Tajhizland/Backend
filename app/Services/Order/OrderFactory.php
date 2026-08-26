<?php

namespace App\Services\Order;

use App\Enums\PaymentGateway;
use App\Events\OrderRequestEvent;
use App\Models\Order;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CouponUser\CouponUserRepositoryInterface;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderInfo\OrderInfoRepositoryInterface;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Order\Data\OrderDraft;
use Carbon\Carbon;

/**
 * تنها جایی که یک سفارش از روی سبد خرید ساخته می‌شود.
 *
 * قبلاً این توالی (ساخت order_info → ساخت order → ثبت کوپن → اتصال سبد →
 * تبدیل cart_item به order_item) در PaymentService پنج بار کپی شده بود و هر نسخه
 * کمی با بقیه فرق داشت.
 */
readonly class OrderFactory implements OrderFactoryInterface
{
    public function __construct(
        private OrderRepositoryInterface       $orderRepository,
        private OrderInfoRepositoryInterface   $orderInfoRepository,
        private CartRepositoryInterface        $cartRepository,
        private CouponUserRepositoryInterface  $couponUserRepository,
        private OnHoldOrderRepositoryInterface $onHoldOrderRepository,
        private CartItemServiceInterface       $cartItemService,
    )
    {
    }

    public function createFromCart(OrderDraft $draft): Order
    {
        $orderInfo = $this->createOrderInfo($draft);
        $order = $this->orderRepository->create($this->attributes($draft, $orderInfo->id));

        if ($draft->coupon) {
            $this->couponUserRepository->create([
                "order_id" => $order->id,
                "user_id" => $draft->user->id,
                "coupon_id" => $draft->coupon->id,
            ]);
        }

        $this->cartRepository->update($draft->cart, ["order_id" => $order->id]);
        $this->cartItemService->convertCartItemToOrderItem($draft->cartItems, $order->id, $draft->disableDiscount);

        return $order;
    }

    /**
     * سفارشِ حاوی کالای محدود را در صف تایید مدیر می‌گذارد.
     */
    public function holdForApproval(Order $order): void
    {
        $onHoldOrder = $this->onHoldOrderRepository->createOnHoldOrder($order->id);
        event(new OrderRequestEvent($onHoldOrder));
    }

    private function createOrderInfo(OrderDraft $draft)
    {
        $user = $draft->user;
        $address = $draft->address;

        return $this->orderInfoRepository->createOrderInfo(
            $user->name,
            $address->mobile,
            $address->tell,
            $address->province_id,
            $address->city_id,
            $address->address,
            $address->zip_code,
            $user->last_name,
            $user->national_code,
        );
    }

    private function attributes(OrderDraft $draft, $orderInfoId): array
    {
        return [
            "user_id" => $draft->user->id,
            "order_info_id" => $orderInfoId,
            "price" => $draft->itemsPrice,
            "delivery_price" => $draft->deliveryPrice,
            "total_price" => $draft->totalPrice,
            "use_wallet_price" => $draft->useWalletPrice,
            "final_price" => $draft->finalPrice,
            "off" => $draft->off,
            "status" => $draft->status->value,
            "payment_method" => PaymentGateway::toValue($draft->paymentMethod) ?? config("settings.default_gateway"),
            "delivery_method" => $draft->deliveryMethod,
            "order_date" => Carbon::now(),
            "delivery_date" => Carbon::now()->addDays($draft->deliveryDelayDays),
            "tracking_number" => "",
        ];
    }
}
