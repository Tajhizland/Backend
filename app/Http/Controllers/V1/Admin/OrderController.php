<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Order\UpdateOrderStatusRequest;
use App\Http\Resources\V1\Order\OrderCollection;
use App\Http\Resources\V1\Order\OrderResource;
use App\Services\Order\OrderServiceInterface;
use Illuminate\Support\Facades\Lang;

class OrderController extends Controller
{
    public function __construct
    (
        private OrderServiceInterface $orderService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new OrderCollection($this->orderService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new OrderResource($this->orderService->findWithDetails($id)));
    }

    public function updateStatus(UpdateOrderStatusRequest $request)
    {
        $this->orderService->updateOrderStatus($request->get("id"),$request->get("status"));
        return $this->successResponse(Lang::get("action.update" , ["attr"=>Lang::get("attr.order_status")]));
    }
}
