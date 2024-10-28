<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardServiceInterface;

class DashboardController extends  Controller
{
    public function __construct
    (
        private DashboardServiceInterface $dashboardService
    )
    {
    }

    public function index()
    {
        return $this->dataResponse($this->dashboardService->chartData());
    }
}
