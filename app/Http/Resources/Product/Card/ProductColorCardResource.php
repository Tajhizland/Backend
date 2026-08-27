<?php

namespace App\Http\Resources\Product\Card;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * نسخه سبک ProductColorResource برای کارت محصول.
 *
 * @mixin \App\Models\ProductColor
 */
class ProductColorCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "product_id" => $this->product_id,
            "color_name" => $this->color_name,
            "color_code" => $this->color_code,
            "delivery_delay" => $this->delivery_delay,
            "status" => $this->status,
            "price" => $this->price?->price,
            "stock" => $this->stock?->stock ?? 0,
            "discountItem" => DiscountItemCardResource::collection($this->whenLoaded("activeDiscountItem")),
        ];
    }
}
