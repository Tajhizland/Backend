<?php

namespace App\Http\Resources\Checkout;

use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\Gateway\GatewayResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Delivery\DeliveryResource;
use App\Http\Resources\CartItem\CartItemResource;

class CheckoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "cartItem" => ["data" => CartItemResource::collection($this->cartItem)],
            "deliveries" => ["data" => DeliveryResource::collection($this->deliveries)],
            "address" => new AddressResource($this->address),
            "gateway" => new GatewayResource($this->gateway),
        ];
    }
}
