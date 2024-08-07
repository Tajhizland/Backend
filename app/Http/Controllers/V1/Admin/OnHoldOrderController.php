<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\OnHoldOrder\OnHoldOrderRequest;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use Illuminate\Support\Facades\Lang;

class OnHoldOrderController extends Controller
{
    public function __construct
    (
        private  OnHoldOrderServiceInterface $onHoldOrderService
    )
    {
    }

    public function dataTable()
    {

    }
    public function accept(OnHoldOrderRequest $request)
    {
        $this->onHoldOrderService->setAccept($request->get("id"));
        return $this->successResponse(Lang::get("action.accept",["attr"=>Lang::get("attr.order_request")]));

    }
    public function reject(OnHoldOrderRequest $request)
    {
        $this->onHoldOrderService->setReject($request->get("id"));
        return $this->successResponse(Lang::get("action.reject",["attr"=>Lang::get("attr.order_request")]));
    }
}
