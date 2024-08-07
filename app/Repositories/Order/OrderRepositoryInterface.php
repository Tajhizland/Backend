<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepositoryInterface;

interface OrderRepositoryInterface extends  BaseRepositoryInterface
{
    public function userOrderPaginate($userId);
    public function setStatus(Order $order,$status);
}
