<?php

namespace App\Http\Resources;

use App\Http\Resources\V1\Order\OrderResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\CouponUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CouponUser */
class CouponUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user_id' => $this->user_id,
            'coupon_id' => $this->coupon_id,
            'order_id' => $this->order_id,

            'user' => new UserResource($this->whenLoaded('user')),
            'coupon' => new CouponResource($this->whenLoaded('coupon')),
            'order' => new OrderResource($this->whenLoaded('order')),
        ];
    }
}
