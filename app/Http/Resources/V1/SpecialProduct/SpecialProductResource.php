<?php

namespace App\Http\Resources\V1\SpecialProduct;

use App\Http\Resources\V1\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\SpecialProduct */
class SpecialProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,

            'product_id' => $this->product_id,
            'homepage' => $this->homepage,

            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
