<?php

namespace App\Http\Resources\V1\PopularProduct;

use App\Http\Resources\V1\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\PopularProduct */
class PopularProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'id' => $this->id,

            'product_id' => $this->product_id,

            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
