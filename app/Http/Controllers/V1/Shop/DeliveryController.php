<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Delivery\SelectDeliveryRequest;
use App\Http\Resources\V1\Delivery\DeliveryCollection;
use App\Services\Cart\CartServiceInterface;
use App\Services\Delivery\DeliveryServiceInterface;
use Illuminate\Support\Facades\Lang;

class DeliveryController extends Controller
{
    public function __construct
    (private DeliveryServiceInterface $deliveryService,
     private CartServiceInterface     $cartService)
    {
    }

    public function getActives()
    {
        return $this->dataResponseCollection(new DeliveryCollection($this->deliveryService->getActives()));
    }

    public function select(SelectDeliveryRequest $request)
    {
        $this->cartService->setDeliveryMethod(\Auth::user()->id,$request->get("id"));
        return $this->successResponse(Lang::get("action.select",["attr"=>Lang::get("attr.delivery")]));
    }
}
