<?php

namespace App\Http\Resources\OnHoldOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/**
 * داده‌ی موردنیاز صفحه‌ی چک‌اوتِ یک سفارش معلقِ تاییدشده.
 *
 * @mixin \App\Models\OnHoldOrder
 */
class OnHoldOrderCheckoutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'expire_date_time' => $this->expire_date,
            'expire_date' => Jalalian::fromDateTime($this->expire_date)->format('Y/m/d H:i:s'),
            'order' => [
                'id' => $this->order->id,
                'status' => $this->order->status,
                'payment_method' => $this->order->payment_method,
                'delivery_method' => $this->order->delivery_method,
                'delivery_price' => $this->order->delivery_price,
                'final_price' => $this->order->final_price,
            ],
            'items' => OnHoldOrderCheckoutItemResource::collection($this->order->orderItems),
        ];
    }
}
