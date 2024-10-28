<?php

namespace App\Services\Dashboard;

use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class DashboardService implements DashboardServiceInterface
{
    public function __construct
    (
        private OrderRepositoryInterface $orderRepository,
        private OnHoldOrderRepositoryInterface $onHoldOrderRepository,
        private CommentRepositoryInterface $commentRepository,
        private UserRepositoryInterface $userRepository,
    )
    {
    }
    public function chartData()
    {
        return [
            "totalPrice"=>$this->orderRepository->totalPriceChartData(),
            "totalCount"=>$this->orderRepository->totalCountChartData(),
            "newOrder"=>$this->orderRepository->todayOrderCount(),
            "newOnHoldOrder"=>$this->onHoldOrderRepository->todayOnHoldOrderCount(),
            "newComment"=>$this->commentRepository->todayCommentCount(),
            "newUser"=>$this->userRepository->todayUserCount(),
        ];
    }
}
