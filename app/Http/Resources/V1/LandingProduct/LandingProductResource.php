<?php

namespace App\Http\Resources\V1\LandingProduct;

use App\Http\Resources\V1\Landing\LandingResource;
use App\Http\Resources\V1\Product\ProductResource;
use App\Models\LandingProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LandingProduct */
class LandingProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'landing_id' => $this->landing_id,
            'product_id' => $this->product_id,

            'landing' => new LandingResource($this->whenLoaded('landing')),
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
