<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Order\DeleteOrderItemRequest;
use App\Http\Requests\V1\Admin\Order\DigipayCalcRequest;
use App\Http\Requests\V1\Admin\Order\UpdateOrderItemRequest;
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
        $this->orderService->updateOrderStatus($request->get("id"), $request->get("status"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.order_status")]));
    }

    public function digipayCalc(DigipayCalcRequest $request)
    {
        $value = $this->orderService->digipayCalc($request->get("start_date"), $request->get("end_date"));
        return $this->dataResponse(["value" => $value]);
    }

    public function updateItem(UpdateOrderItemRequest $request)
    {
        $order = $this->orderService->updateOrderItem($request->get("id"), $request->get("count"));
        return $this->dataResponse(new OrderResource($order), Lang::get("action.update", ["attr" => Lang::get("attr.order_item")]));
    }

    public function deleteItem(DeleteOrderItemRequest $request)
    {
        $order = $this->orderService->deleteOrderItem($request->get("id"));
        return $this->dataResponse(new OrderResource($order), Lang::get("action.remove", ["attr" => Lang::get("attr.order_item")]));
    }
}
