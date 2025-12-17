<?php

namespace App\Http\Resources\V1\DiscountItem;

use App\Http\Resources\V1\Discount\DiscountResource;
use App\Http\Resources\V1\ProductColor\ProductColorResource;
use App\Models\DiscountItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin DiscountItem */
class DiscountItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'discount_price' => $this->discount_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'discount_id' => $this->discount_id,
            'product_color_id' => $this->product_color_id,
            'discount_expire_time' => $this->discount_expire_time,
            'top' => $this->top,
            'discount_expire_time_fa' => $this->discount_expire_time != null ? Jalalian::fromDateTime($this->discount_expire_time)->format('Y/m/d H:i') : "",

            'discount' => new DiscountResource($this->whenLoaded('discount')),
            'productColor' => new ProductColorResource($this->whenLoaded('productColor')),
        ];
    }
}
