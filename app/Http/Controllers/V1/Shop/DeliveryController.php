<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Delivery\SelectDeliveryRequest;
use App\Services\Cart\CartServiceInterface;
use App\Services\Delivery\DeliveryServiceInterface;
use App\Http\Resources\Delivery\DeliveryResource;

class DeliveryController extends Controller
{
    public function __construct
    (private readonly DeliveryServiceInterface $deliveryService,
     private readonly CartServiceInterface     $cartService)
    {
    }

    public function getActives()
    {
        return $this->dataResponseCollection(DeliveryResource::collection($this->deliveryService->getActives()));
    }

    public function select(SelectDeliveryRequest $request)
    {
        $this->cartService->setDeliveryMethod(\Auth::user()->id,$request->get("id"));
        return $this->successResponse(__("action.select",["attr"=>__("attr.delivery")]));
    }
}
