<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Exceptions\BreakException;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface     $orderRepository,
        private OrderItemRepositoryInterface $orderItemRepository
    )
    {
    }

    public function userOrderPaginate($userId)
    {
        return $this->orderRepository->userOrderPaginate($userId);
    }

    public function findById($id)
    {
        $order = $this->orderRepository->findOrFail($id);
//        Gate::authorize("view", $order);
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
            throw new  BreakException($throwable->getMessage());
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

    public function updateOrderItem($itemId, $count)
    {
        return DB::transaction(function () use ($itemId, $count) {
            $item = $this->orderItemRepository->findOrFail($itemId);

            if ($count > $item->count) {
                throw new BreakException(__("action.order_item_only_decrease"));
            }

            $this->orderItemRepository->update($item, ["count" => $count]);

            return $this->recalculateOrderPrices($item->order_id);
        });
    }

    public function deleteOrderItem($itemId)
    {
        return DB::transaction(function () use ($itemId) {
            $item = $this->orderItemRepository->findOrFail($itemId);
            $orderId = $item->order_id;

            $this->orderItemRepository->delete($item);

            return $this->recalculateOrderPrices($orderId);
        });
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

        return $this->orderRepository->findWithDetails($orderId);
    }
}
