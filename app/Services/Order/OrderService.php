<?php

namespace App\Services\Order;

use App\DTOs\Order\DigipayCalcDto;
use App\DTOs\Order\OrderItemUpdateDto;
use App\DTOs\Order\OrderStatusUpdateDto;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Services\SnappPay\SnappPayService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private OrderRepositoryInterface     $orderRepository,
        private OrderItemRepositoryInterface $orderItemRepository,
        private SnappPayService              $snappPayService,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->orderRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        return $order;
    }

    public function findWithDetails(int $id): mixed
    {
        $order = $this->orderRepository->findWithDetails($id);
        if (!$order) {
            throw new NotFoundHttpException();
        }
        return $order;
    }

    public function userOrderPaginate(int $userId): mixed
    {
        return $this->orderRepository->userOrderPaginate($userId);
    }

    public function updateStatus(OrderStatusUpdateDto $dto): bool
    {
        $order = $this->find($dto->orderId);
        $status = OrderStatus::tryFrom($dto->status);
        if (!$status) {
            throw new BadRequestHttpException(__("exceptions.invalid_order_status"));
        }
        return $this->orderRepository->updateOrderStatus($order, $status->value);
    }

    public function setDeliveryToken(int $id, $token): bool
    {
        $order = $this->find($id);
        return $this->orderRepository->update($order, ["delivery_token" => $token]);
    }

    public function digipayCalc(DigipayCalcDto $dto): mixed
    {
        return $this->orderRepository->digipaySumOrder($dto->start_date, $dto->end_date);
    }

    public function cancel(int $id): mixed
    {
        DB::transaction(function () use ($id) {
            $order = $this->find($id);
            $this->orderRepository->setStatus($order, OrderStatus::Cancelled->value);
            $this->cancelSnappPayPayment($order);
        });

        return $this->findWithDetails($id);
    }

    public function updateItem(OrderItemUpdateDto $dto): mixed
    {
        $orderId = DB::transaction(function () use ($dto) {
            $item = $this->orderItemRepository->findOrFail($dto->orderItemId);

            if ($dto->count > $item->count) {
                throw new BadRequestHttpException(__("action.order_item_only_decrease"));
            }

            $this->orderItemRepository->update($item, ["count" => $dto->count]);
            $this->recalculateOrderPrices($item->order_id);
            $this->syncSnappPayPayment($item->order_id);

            return $item->order_id;
        });

        return $this->findWithDetails($orderId);
    }

    public function deleteItem(int $itemId): mixed
    {
        $orderId = DB::transaction(function () use ($itemId) {
            $item = $this->orderItemRepository->findOrFail($itemId);
            $orderId = $item->order_id;

            if ($this->orderItemRepository->getByOrderId($orderId)->count() <= 1) {
                throw new BadRequestHttpException(__("action.order_item_last_cannot_delete"));
            }

            $this->orderItemRepository->delete($item);
            $this->recalculateOrderPrices($orderId);
            $this->syncSnappPayPayment($orderId);

            return $orderId;
        });

        return $this->findWithDetails($orderId);
    }

    private function recalculateOrderPrices($orderId): void
    {
        $order = $this->find($orderId);

        $itemsPrice = $this->orderItemRepository->sumFinalPrice($orderId);
        $totalPrice = max(0, $itemsPrice + $order->delivery_price - $order->off);
        $finalPrice = max(0, $totalPrice - $order->use_wallet_price);

        $this->orderRepository->update($order, [
            "price" => $itemsPrice,
            "total_price" => $totalPrice,
            "final_price" => $finalPrice,
        ]);
    }

    private function syncSnappPayPayment($orderId): void
    {
        $order = $this->find($orderId);

        if (!$this->hasSnappPayPayment($order)) {
            return;
        }

        $orderItems = $this->orderItemRepository->getByOrderId($orderId);
        $result = $this->snappPayService->update($orderId, $orderItems, $order->final_price * 10);

        $this->assertSnappPaySuccessful($result);
    }

    private function cancelSnappPayPayment($order): void
    {
        if (!$this->hasSnappPayPayment($order)) {
            return;
        }

        $result = $this->snappPayService->cancel($order->id);

        $this->assertSnappPaySuccessful($result);
    }

    private function hasSnappPayPayment($order): bool
    {
        return PaymentGateway::normalize($order->payment_method) === PaymentGateway::SnappPay
            && !empty($order->payment_token);
    }

    /**
     * در صورت خطای اسنپ‌پی استثنا پرتاب می‌شود تا تراکنش دیتابیس رول‌بک شود
     * و ویرایش/کنسلی سفارش انجام نشود.
     */
    private function assertSnappPaySuccessful(mixed $result): void
    {
        if (is_array($result) && ($result["successful"] ?? false) === true) {
            return;
        }

        throw new BadRequestHttpException(__("exceptions.snapppay_error"));
    }
}
