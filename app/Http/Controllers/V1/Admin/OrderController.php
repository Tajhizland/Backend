<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Order\DigipayCalcDto;
use App\DTOs\Order\OrderItemUpdateDto;
use App\DTOs\Order\OrderStatusUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\DigipayCalcRequest;
use App\Http\Requests\Admin\Order\UpdateOrderItemRequest;
use App\Http\Requests\Admin\Order\UpdateOrderStatusRequest;
use App\Http\Resources\Order\OrderResource;
use App\Services\Order\OrderServiceInterface;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(OrderResource::collection($this->orderService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new OrderResource($this->orderService->findWithDetails($id)));
    }

    public function updateStatus($id, UpdateOrderStatusRequest $request)
    {
        $dto = new OrderStatusUpdateDto($id, ...$request->validated());
        $this->orderService->updateStatus($dto);
        return $this->successResponse(__("action.update", ["attr" => __("attr.order_status")]));
    }

    public function cancel($id)
    {
        $order = $this->orderService->cancel($id);
        return $this->dataResponse(new OrderResource($order), __("action.cancel", ["attr" => __("attr.order")]));
    }

    public function updateItem($id, UpdateOrderItemRequest $request)
    {
        $dto = new OrderItemUpdateDto($id, ...$request->validated());
        $order = $this->orderService->updateItem($dto);
        return $this->dataResponse(new OrderResource($order), __("action.update", ["attr" => __("attr.order_item")]));
    }

    public function deleteItem($id)
    {
        $order = $this->orderService->deleteItem($id);
        return $this->dataResponse(new OrderResource($order), __("action.remove", ["attr" => __("attr.order_item")]));
    }

    public function digipayCalc(DigipayCalcRequest $request)
    {
        $dto = new DigipayCalcDto(...$request->validated());
        return $this->dataResponse(["value" => $this->orderService->digipayCalc($dto)]);
    }
}
