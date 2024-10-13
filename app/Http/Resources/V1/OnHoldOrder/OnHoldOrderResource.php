<?php

namespace App\Http\Resources\V1\OnHoldOrder;

use App\Http\Resources\V1\Order\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\OnHoldOrder */
class OnHoldOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order' => new OrderResource($this->whenLoaded('order')),
            'status' => $this->status,
            'expire_date_time' =>$this->expire_date,
            'expire_date' => Jalalian::fromDateTime($this->expire_date)->format('Y/m/d H:i:s'),
            'review_date' => Jalalian::fromDateTime($this->review_date)->format('Y/m/d H:i:s'),
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
