<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardServiceInterface;
use Illuminate\Http\Request;

class DashboardController extends  Controller
{
    public function __construct
    (
        private DashboardServiceInterface $dashboardService
    )
    {
    }

    public function index(Request $request)
    {


        return $this->dataResponse($this->dashboardService->chartData($request->fromDate , $request->toDate));
    }
}
