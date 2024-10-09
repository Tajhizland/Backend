<?php

namespace App\Services\Order;

use App\Models\Order;

interface OrderServiceInterface
{
    public function userOrderPaginate($userId);
    public function findById($id);
    public function findWithDetails($id);
    public function dataTable();
    public function updateOrderStatus($id , $status);
}
