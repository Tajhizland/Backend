<?php

namespace App\Services\Dashboard;

use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Footprint\FootprintRepositoryInterface;
use App\Repositories\OnHoldOrder\OnHoldOrderRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

class DashboardService implements DashboardServiceInterface
{
    public function __construct
    (
        private OrderRepositoryInterface       $orderRepository,
        private OnHoldOrderRepositoryInterface $onHoldOrderRepository,
        private CommentRepositoryInterface     $commentRepository,
        private UserRepositoryInterface        $userRepository,
        private FootprintRepositoryInterface   $footprintRepository,
    )
    {
    }

    public function chartData($fromDate,$toDate)
    {
        return [
            "totalPrice" => $this->orderRepository->totalPriceChartData($fromDate,$toDate),
            "totalCount" => $this->orderRepository->totalCountChartData($fromDate,$toDate),
            "newOrder" => $this->orderRepository->todayOrderCount(),
            "newOnHoldOrder" => $this->onHoldOrderRepository->todayOnHoldOrderCount(),
            "newComment" => $this->commentRepository->todayCommentCount(),
            "newUser" => $this->userRepository->todayUserCount(),
            "viewLog" => $this->footprintRepository->allChartData($fromDate,$toDate),
            "viewIpLog" => $this->footprintRepository->ipChartData($fromDate,$toDate),
        ];
    }
}
