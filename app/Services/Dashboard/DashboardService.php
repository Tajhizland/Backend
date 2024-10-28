<?php

namespace App\Services\Dashboard;

use App\Repositories\Order\OrderRepositoryInterface;

class DashboardService implements DashboardServiceInterface
{
    public function __construct
    (
        private OrderRepositoryInterface $orderRepository
    )
    {
    }
    public function chartData()
    {
        return [
            "totalPrice"=>$this->orderRepository->totalPriceChartData(),
            "totalCount"=>$this->orderRepository->totalCountChartData(),
        ];
    }
}
