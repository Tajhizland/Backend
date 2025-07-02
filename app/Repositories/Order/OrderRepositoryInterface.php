<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepositoryInterface;

interface OrderRepositoryInterface extends  BaseRepositoryInterface
{
    public function updateOrderStatus(Order $order , $status);
    public function findWithDetails($id);
    public function userOrderPaginate($userId);
    public function setStatus(Order $order,$status);
    public function dataTable();
    public function onHoldDataTable();
    public function createOrder($user_id,$order_info_id,$price,$delivery_price,$final_price,$status,$payment_method,$delivery_method,$order_date,$delivery_date,$tracking_number,$total_price=0,$use_wallet_price=0);

    public function totalPriceChartData($fromDate,$toDate);
    public function totalCountChartData($fromDate,$toDate);
    public function todayOrderCount();
}
