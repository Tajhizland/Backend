<?php

namespace App\Services\OnHoldOrder;

use App\Enums\OrderStatus;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Gate;

class OnHoldOrderService implements OnHoldOrderServiceInterface
{
    public function __construct(
        private OnHoldOrderRepositoryInterface $onHoldOrderRepository,
        private SmsServiceInterface            $smsService,
        private OrderRepositoryInterface       $orderRepository
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
        $this->orderRepository->setStatus($onHoldOrder->order, OrderStatus::Unpaid->value);
        return $this->onHoldOrderRepository->delete($onHoldOrder);
    }

    public function setAccept($id)
    {
        $message = "سفارش معلق شما در تجهیزلند توسط مدیریت تایید شد . شما اکنون میتوانید با پرداخت مبلغ سفارش ,  سفارش خود را تکمیل کنید
        https://tajhizland.com/account-order-on-hold";
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        Gate::authorize("update", $onHoldOrder);
        $this->orderRepository->setStatus($onHoldOrder->order, OrderStatus::Accepted->value);
        $this->smsService->send($onHoldOrder->order->orderInfo->mobile, $message);
        return $this->onHoldOrderRepository->setAccept($onHoldOrder);
    }

    public function setReject($id)
    {
        $message = "سفارش معلق شما در تجهیزلند توسط مدیریت رد شد .
        https://tajhizland.com/account-order-on-hold";
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        Gate::authorize("update", $onHoldOrder);
        $this->orderRepository->setStatus($onHoldOrder->order, OrderStatus::Rejected->value);
        $this->smsService->send($onHoldOrder->order->orderInfo->mobile, $message);
        return $this->onHoldOrderRepository->setReject($onHoldOrder);
    }

    public function dataTable()
    {
        return $this->onHoldOrderRepository->dataTable();
    }

    public function findOrderById($id)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findOrFail($id);
        return $this->orderRepository->findWithDetails($onHoldOrder->order_id);
    }

    public function getByUserId($userId)
    {
        return $this->onHoldOrderRepository->getByUserId($userId);
    }
}
