<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepositoryInterface;

interface OrderRepositoryInterface extends BaseRepositoryInterface
{
    public function updateOrderStatus(Order $order, $status);

    public function findWithDetails($id);

    public function userOrderPaginate($userId);

    public function setStatus(Order $order, $status);

    public function dataTable();

    public function onHoldDataTable();

    public function totalPriceChartData($fromDate, $toDate);

    public function totalCountChartData($fromDate, $toDate);

    public function todayOrderCount();

    public function digipaySumOrder($startDate, $endDate);
}
