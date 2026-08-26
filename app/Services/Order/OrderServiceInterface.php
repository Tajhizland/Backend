<?php

namespace App\Services\Order;

use App\DTOs\Order\DigipayCalcDto;
use App\DTOs\Order\OrderItemUpdateDto;
use App\DTOs\Order\OrderStatusUpdateDto;

interface OrderServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function findWithDetails(int $id): mixed;

    public function userOrderPaginate(int $userId): mixed;

    public function updateStatus(OrderStatusUpdateDto $dto): bool;

    public function setDeliveryToken(int $id, $token): bool;

    public function cancel(int $id): mixed;

    public function updateItem(OrderItemUpdateDto $dto): mixed;

    public function deleteItem(int $itemId): mixed;

    public function digipayCalc(DigipayCalcDto $dto): mixed;
}
