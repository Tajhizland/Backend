<?php

namespace App\Http\Resources\V1\Order;

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
            'discount' => $this->discount,
            'final_price' => $this->final_price,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'order_date' => $this->order_date,
            'tracking_number' => $this->tracking_number,
             'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
