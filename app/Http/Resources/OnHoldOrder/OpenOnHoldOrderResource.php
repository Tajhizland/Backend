<?php

namespace App\Http\Resources\OnHoldOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\OnHoldOrder */
class OpenOnHoldOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->order->orderItems;

        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'expire_date_time' => $this->expire_date,
            'expire_date' => $this->expire_date ? Jalalian::fromDateTime($this->expire_date)->format('Y/m/d H:i') : null,
            'items_count' => (int) $items->sum('count'),
            'product_names' => $items->map(fn ($item) => $item->product?->name)->filter()->unique()->take(2)->values(),
        ];
    }
}
