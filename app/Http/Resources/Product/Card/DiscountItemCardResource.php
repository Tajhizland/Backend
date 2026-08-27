<?php

namespace App\Http\Resources\Product\Card;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * نسخه سبک DiscountItemResource برای کارت محصول.
 *
 * @mixin \App\Models\DiscountItem
 */
class DiscountItemCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "product_color_id" => $this->product_color_id,
            "discount_id" => $this->discount_id,
            "discount_price" => $this->discount_price,
            "discount_expire_time" => $this->discount_expire_time,
            "top" => $this->top,
        ];
    }
}
