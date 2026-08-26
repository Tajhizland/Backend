<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnHoldOrder\OnHoldOrderResource;
use App\Http\Resources\Order\OrderResource;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;

class OnHoldOrderController extends Controller
{
    public function __construct
    (
        private readonly OnHoldOrderServiceInterface $onHoldOrderService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(OnHoldOrderResource::collection($this->onHoldOrderService->dataTable())) ;
    }
    public function show($id)
    {
        return $this->dataResponse(new OrderResource($this->onHoldOrderService->findOrderById($id)));
    }
    public function accept($id)
    {
        $this->onHoldOrderService->setAccept($id);
        return $this->successResponse(__("action.accept",["attr"=>__("attr.order_request")]));

    }
    public function reject($id)
    {
        $this->onHoldOrderService->setReject($id);
        return $this->successResponse(__("action.reject",["attr"=>__("attr.order_request")]));
    }
}
