<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OnHoldOrder\OnHoldOrderCollection;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class OnHoldOrderController extends Controller
{
    public function __construct(
        private OnHoldOrderServiceInterface $onHoldOrderService
    )
    {
    }

    public function userHoldOnPaginate()
    {
        return $this->dataResponse(
            new OnHoldOrderCollection($this->onHoldOrderService->userHoldOnPaginate(Auth::user()->id))
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
