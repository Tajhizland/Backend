<?php

namespace App\Services\OnHoldOrder;

use App\Enums\OrderStatus;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class OnHoldOrderService implements OnHoldOrderServiceInterface
{
    public function __construct(
        private OnHoldOrderRepositoryInterface $onHoldOrderRepository,
        private  OrderRepositoryInterface $orderRepository
    )
    {
    }
    public function findById($id)
    {
        return $this->onHoldOrderRepository->findOrFail($id);
    }

    public function userHoldOnPaginate($userId)
    {
        return $this->onHoldOrderRepository->userOnHoldOrderPaginate($userId);
    }

    public function removeItem($id)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        Gate::authorize("delete", $onHoldOrder);
        $this->orderRepository->setStatus($onHoldOrder->order,OrderStatus::Unpaid->value);
        return $this->onHoldOrderRepository->delete($onHoldOrder);
    }

    public function setAccept($id)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        Gate::authorize("update", $onHoldOrder);
        $this->orderRepository->setStatus($onHoldOrder->order,OrderStatus::Accepted->value);
        return $this->onHoldOrderRepository->setAccept($onHoldOrder);
    }

    public function setReject($id)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        Gate::authorize("update", $onHoldOrder);
        $this->orderRepository->setStatus($onHoldOrder->order,OrderStatus::Rejected->value);
        return $this->onHoldOrderRepository->setReject($onHoldOrder);
    }

    public function dataTable()
    {
        return $this->onHoldOrderRepository->dataTable();
    }

    public function findOrderById($id)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        return $this->orderRepository->findOrFail($onHoldOrder->order_id);
    }
}
