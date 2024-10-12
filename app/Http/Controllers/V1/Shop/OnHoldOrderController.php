<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OnHoldOrder\OnHoldOrderCollection;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Payment\PaymentServicesInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class OnHoldOrderController extends Controller
{
    public function __construct(
        private OnHoldOrderServiceInterface $onHoldOrderService,
                private PaymentServicesInterface $paymentServices

    )
    {
    }

    public function userHoldOnPaginate()
    {
        return $this->dataResponseCollection(
            new OnHoldOrderCollection($this->onHoldOrderService->userHoldOnPaginate(Auth::user()->id))
        );
    }
    public function payment($id)
    {
        $paymentPath = $this->paymentServices->onHoldOrderRequest($id , Auth::user()->id);
        return $this->dataResponse(
            [
                "path" => $paymentPath
            ]
        );
    }

    public function remove($id)
    {
        return $this->dataResponse(
            new OnHoldOrderCollection($this->onHoldOrderService->userHoldOnPaginate($id)),
            Lang::get("action.remove",["attr"=>Lang::get("attr.order_request")])
        );
    }
}
