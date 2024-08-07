<?php

namespace App\Services\Order;

use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class OrderService implements OrderServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    )
    {
        //
    }

    public function userOrderPaginate($userId)
    {
        return $this->orderRepository->userOrderPaginate($userId);
    }
    public function findById($id)
    {
        $order= $this->orderRepository->findOrFail($id);
        Gate::authorize("view",$order);
        return $order;
    }

}
