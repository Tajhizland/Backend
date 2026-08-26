<?php

namespace App\Services\Order;


interface OrderServiceInterface
{
    public function userOrderPaginate($userId);

    public function findById($id);

    public function findUserOrder($id);

    public function findWithDetails($id);

    public function dataTable();

    public function setDeliveryToken($id, $token);

    public function updateOrderStatus($id, $status);

    public function digipayCalc($startDate, $endDate);

    public function cancelOrder($id);

    public function updateOrderItem($itemId, $count);

    public function deleteOrderItem($itemId);
}
