<?php

namespace App\Http\Resources\V1\Order;

use App\Http\Resources\V1\Delivery\DeliveryResource;
use App\Http\Resources\V1\Gateway\GatewayResource;
use App\Http\Resources\V1\OrderInfo\OrderInfoResource;
use App\Http\Resources\V1\OrderItem\OrderItemCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_info_id' => $this->order_info_id,
            'price' => $this->price,
            'delivery_price' => $this->delivery_price,
            'final_price' => $this->final_price,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'delivery_method' => $this->delivery_method,
            'order_date' => Jalalian::fromDateTime($this->order_date)->format('Y/m/d  H:i:s'),
            'delivery_date' => Jalalian::fromDateTime($this->delivery_date)->format('Y/m/d'),
            'tracking_number' => $this->tracking_number,
            'orderItems' => new OrderItemCollection($this->whenLoaded('orderItems')),
            'orderInfo' => new OrderInfoResource($this->whenLoaded('orderInfo')),
            'delivery' => new DeliveryResource($this->whenLoaded('delivery')),
            'payment' => new GatewayResource($this->whenLoaded('payment')),

            'created_at' => Jalalian::fromDateTime($this->created_at->timezone(config('app.timezone')))->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
