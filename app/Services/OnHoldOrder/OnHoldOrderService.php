<?php

namespace App\Services\OnHoldOrder;

use App\Enums\OnHoldOrderStatus;
use App\Enums\OrderStatus;
use App\Exceptions\BreakException;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Gate;

readonly class OnHoldOrderService implements OnHoldOrderServiceInterface
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

    /**
     * سفارش معلقِ آماده‌ی پرداخت، با همه‌ی رابطه‌های موردنیاز صفحه‌ی چک‌اوت.
     * اگر سفارش مال کاربر نباشد، تایید نشده باشد یا مهلت پرداختش گذشته باشد خطا می‌دهد.
     */
    public function checkoutData($id, $userId)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findWithCheckoutRelations($id);
        $this->assertPayable($onHoldOrder, $userId);
        return $onHoldOrder;
    }

    /**
     * اقلام سفارش معلق — برای محاسبه‌ی روش‌های ارسال.
     */
    public function checkoutItems($id, $userId)
    {
        $onHoldOrder = $this->onHoldOrderRepository->findWithCheckoutRelations($id);
        $this->assertPayable($onHoldOrder, $userId);
        return $onHoldOrder->order->orderItems;
    }

    /**
     * شرط‌های لازم برای اینکه یک سفارش معلق قابل پرداخت باشد.
     */
    public function assertPayable($onHoldOrder, $userId)
    {
        if (!$onHoldOrder->order || $onHoldOrder->order->user_id != $userId) {
            throw new BreakException(\Lang::get("exceptions.not_your_order"));
        }
        if ($onHoldOrder->status != OnHoldOrderStatus::Accept->value) {
            throw new BreakException(\Lang::get("exceptions.reject_order"));
        }
        if (!$onHoldOrder->expire_date || $this->expireTimestamp($onHoldOrder) < now()->getTimestamp()) {
            throw new BreakException(\Lang::get("exceptions.expired_order"));
        }
        if (!in_array($onHoldOrder->order->status, [
            OrderStatus::Unpaid->value,
            OrderStatus::OnHold->value,
            OrderStatus::Accepted->value,
        ])) {
            throw new BreakException("این سفارش قابل پرداخت نیست");
        }
        return true;
    }

    /**
     * expire_date روی مدل به timestamp کست شده، ولی ممکن است رشته هم باشد.
     */
    private function expireTimestamp($onHoldOrder): int
    {
        $expire = $onHoldOrder->expire_date;
        return is_numeric($expire) ? (int)$expire : strtotime($expire);
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
