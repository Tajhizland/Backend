<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Order\OrderCollection;
use App\Http\Resources\V1\Order\OrderResource;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct
    (
        private OrderServiceInterface $orderService
    )
    {
    }

    public function userOrderPaginate()
    {
        return $this->dataResponseCollection(
            new OrderCollection($this->orderService->userOrderPaginate(Auth::user()->id))
        );
    }
    public function findById($id)
    {
        return $this->dataResponse(
            new OrderResource($this->orderService->findById($id))
        );
    }
    public function userOrders()
    {
        return $this->dataResponse(
            new OrderResource($this->orderService->userOrderPaginate(Auth::user()->id))
        );
    }
}
