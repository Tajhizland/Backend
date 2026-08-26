<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService,
    )
    {
    }

    public function index()
    {
        return $this->dataResponseCollection(OrderResource::collection($this->orderService->userOrderPaginate(Auth::user()->id)));
    }

    public function show($id)
    {
        $order = $this->orderService->findWithDetails($id);
        $this->authorize("view", $order);
        return $this->dataResponse(new OrderResource($order));
    }
}
