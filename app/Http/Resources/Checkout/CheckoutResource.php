<?php

namespace App\Http\Resources\Checkout;

use App\Http\Resources\Address\AddressResource;
use App\Http\Resources\CartItem\CartItemCollection;
use App\Http\Resources\Delivery\DeliveryCollection;
use App\Http\Resources\Gateway\GatewayResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "cartItem" => new CartItemCollection($this->cartItem),
            "deliveries" => new DeliveryCollection($this->deliveries),
            "address" => new AddressResource($this->address),
            "gateway" => new GatewayResource($this->gateway),
        ];
    }
}
