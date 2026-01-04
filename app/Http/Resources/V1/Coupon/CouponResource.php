<?php

namespace App\Http\Resources\V1\Coupon;

use App\Http\Resources\V1\User\UserResource;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin Coupon */
class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'status' => $this->status,
            'price' => $this->price,
            'percent' => $this->percent,
            'min_order_value' => $this->min_order_value,
            'max_order_value' => $this->max_order_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user_id' => $this->user_id,

            'created_at_fa' => $this->created_at != null ? Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i') : "",
            'start_time_fa' => $this->start_time != null ? Jalalian::fromDateTime($this->start_time)->format('Y/m/d H:i') : "",
            'end_time_fa' => $this->end_time != null ? Jalalian::fromDateTime($this->end_time)->format('Y/m/d H:i') : "",

            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
