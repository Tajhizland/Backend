<?php

namespace App\Services\Order;

interface OrderServiceInterface
{
    public function userOrderPaginate($userId);
    public function findById($id);
}
