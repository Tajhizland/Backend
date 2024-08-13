<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepositoryInterface;

interface OrderRepositoryInterface extends  BaseRepositoryInterface
{
    public function updateOrderStatus(Order $order , $status);

    public function userOrderPaginate($userId);
    public function setStatus(Order $order,$status);
    public function dataTable();
    public function onHoldDataTable();
    public function createOrder($user_id,$order_info_id,$price,$discount,$total_price,$final_price,$status,$payment_method,$delivery_method,$delivery_price,$order_date,$tracking_number=null);
}
