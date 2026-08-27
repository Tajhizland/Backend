<?php

namespace App\Http\Resources\Product\Card;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\ProductImage
 */
class ProductImageCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "url" => $this->url,
            "product_id" => $this->product_id,
            "product_color_id" => $this->product_color_id,
        ];
    }
}
