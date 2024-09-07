<?php

namespace App\Http\Resources\V1\OnHoldOrder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OnHoldOrder */
class OnHoldOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'status' => $this->status,
            'expire_date' => $this->expire_date,
            'review_date' => $this->review_date,
             'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
