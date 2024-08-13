<?php

namespace App\Services\Order;

use App\Enums\OrderStatus;
use App\Exceptions\BreakException;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
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
        Gate::authorize("view", $order);
        return $order;
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
}
