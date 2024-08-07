<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderServiceInterface;

class OrderController extends Controller
{
    public function __construct
    (
        private  OrderServiceInterface $orderService
    )
    {
    }

    public function index()
    {

    }
}
