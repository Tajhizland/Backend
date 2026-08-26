<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Delivery\DeliveryResource;
use App\Http\Resources\Gateway\GatewayResource;
use App\Http\Resources\OrderInfo\OrderInfoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use App\Http\Resources\OrderItem\OrderItemResource;

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
            'total_price' => $this->total_price,
            'use_wallet_price' => $this->use_wallet_price,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'delivery_method' => $this->delivery_method,
            'order_date' => Jalalian::fromDateTime($this->order_date)->format('Y/m/d  H:i:s'),
            'delivery_date' => Jalalian::fromDateTime($this->delivery_date)->format('Y/m/d'),
            'tracking_number' => $this->tracking_number,
            'orderItems' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'orderInfo' => new OrderInfoResource($this->whenLoaded('orderInfo')),
            'delivery' => new DeliveryResource($this->whenLoaded('delivery')),
            'payment' => new GatewayResource($this->whenLoaded('payment')),

            'created_at' => Jalalian::fromDateTime($this->created_at->timezone(config('app.timezone')))->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
