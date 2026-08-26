<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Exceptions\BreakException;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Services\SnappPay\SnappPayService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface     $orderRepository,
        private OrderItemRepositoryInterface $orderItemRepository,
        private SnappPayService              $snappPayService
    )
    {
    }

    public function userOrderPaginate($userId)
    {
        return $this->orderRepository->userOrderPaginate($userId);
    }

    /**
     * بدون بررسی مالکیت. فقط برای مصرف داخلی/ادمین.
     */
    public function findById($id)
    {
        return $this->orderRepository->findOrFail($id);
    }

    /**
     * سفارشِ کاربرِ احرازهویت‌شده.
     *
     * اندپوینت‌های فروشگاه باید از این استفاده کنند: OrderPolicy::view بررسی می‌کند
     * سفارش متعلق به همان کاربر باشد، وگرنه ۴۰۳ می‌دهد.
     */
    public function findUserOrder($id)
    {
        $order = $this->orderRepository->findOrFail($id);
        Gate::authorize("view", $order);
        return $order;
    }

    public function findWithDetails($id)
    {
        return $this->orderRepository->findWithDetails($id);
    }

    public function dataTable()
    {
        return $this->orderRepository->dataTable();
    }

    public function updateOrderStatus($id, $status)
    {
        $order = $this->orderRepository->findOrFail($id);
        try {
            $status = OrderStatus::from($status);
        } catch (\Throwable $throwable) {
            throw new BreakException($throwable->getMessage());
        }
        return $this->orderRepository->updateOrderStatus($order, $status->value);
    }

    public function setDeliveryToken($id, $token)
    {
        $order = $this->orderRepository->findOrFail($id);
        return $this->orderRepository->update($order, ["delivery_token" => $token]);
    }

    public function digipayCalc($startDate, $endDate)
    {
        return $this->orderRepository->digipaySumOrder($startDate, $endDate);
    }

    public function cancelOrder($id)
    {
        DB::transaction(function () use ($id) {
            $order = $this->orderRepository->findOrFail($id);
            $this->orderRepository->setStatus($order, OrderStatus::Cancelled->value);
        });

        $order = $this->orderRepository->findOrFail($id);
        if (PaymentGateway::normalize($order->payment_method) === PaymentGateway::SnappPay) {
            $this->snappPayService->cancel($id);
        }

        return $this->orderRepository->findWithDetails($id);
    }

    public function updateOrderItem($itemId, $count)
    {
        $orderId = DB::transaction(function () use ($itemId, $count) {
            $item = $this->orderItemRepository->findOrFail($itemId);

            if ($count > $item->count) {
                throw new BreakException(__("action.order_item_only_decrease"));
            }

            $this->orderItemRepository->update($item, ["count" => $count]);
            $this->recalculateOrderPrices($item->order_id);

            return $item->order_id;
        });

        $this->syncSnappPayPayment($orderId);

        return $this->orderRepository->findWithDetails($orderId);
    }

    public function deleteOrderItem($itemId)
    {
        $orderId = DB::transaction(function () use ($itemId) {
            $item = $this->orderItemRepository->findOrFail($itemId);
            $orderId = $item->order_id;

            // سفارش باید همیشه حداقل یک آیتم داشته باشد؛ آخرین محصول قابل حذف نیست
            if ($this->orderItemRepository->getByOrderId($orderId)->count() <= 1) {
                throw new BreakException(__("action.order_item_last_cannot_delete"));
            }

            $this->orderItemRepository->delete($item);
            $this->recalculateOrderPrices($orderId);

            return $orderId;
        });

        $this->syncSnappPayPayment($orderId);

        return $this->orderRepository->findWithDetails($orderId);
    }

    /**
     * Recompute the order invoice amounts based on its current items.
     *
     * price        = مجموع مبلغ نهایی آیتم‌ها (final_price * count)
     * total_price  = مبلغ کل سفارش (آیتم‌ها + هزینه ارسال - تخفیف)
     * final_price  = مبلغ قابل پرداخت (مبلغ کل - مبلغ استفاده‌شده از کیف پول)
     */
    private function recalculateOrderPrices($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        $itemsPrice = $this->orderItemRepository->sumFinalPrice($orderId);
        $totalPrice = max(0, $itemsPrice + $order->delivery_price - $order->off);
        $finalPrice = max(0, $totalPrice - $order->use_wallet_price);

        $this->orderRepository->update($order, [
            "price" => $itemsPrice,
            "total_price" => $totalPrice,
            "final_price" => $finalPrice,
        ]);
    }

    /**
     * اگر درگاه پرداخت سفارش اسنپ‌پی (۴) باشد، مبالغ به‌روزشده را به اسنپ‌پی هم اعلام می‌کنیم.
     * این فراخوانی بعد از کامیت تراکنش انجام می‌شود تا تماس شبکه‌ای داخل تراکنش نباشد.
     */
    private function syncSnappPayPayment($orderId)
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if (PaymentGateway::normalize($order->payment_method) !== PaymentGateway::SnappPay) {
            return;
        }

        $orderItems = $this->orderItemRepository->getByOrderId($orderId);
        $this->snappPayService->update($orderId, $orderItems, $order->final_price * 10);
    }
}
