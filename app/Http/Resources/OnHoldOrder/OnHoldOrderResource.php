<?php

namespace App\Http\Resources\OnHoldOrder;

use App\Http\Resources\Order\OrderResource;
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
            'expire_date_time' => $this->expire_date,
            // تاریخ‌های خالی نباید به «الان» تبدیل شوند؛ Jalalian با ورودی null زمان جاری می‌دهد.
            'expire_date' => $this->expire_date ? Jalalian::fromDateTime($this->expire_date)->format('Y/m/d H:i:s') : null,
            'review_date' => $this->review_date ? Jalalian::fromDateTime($this->review_date)->format('Y/m/d H:i:s') : null,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
