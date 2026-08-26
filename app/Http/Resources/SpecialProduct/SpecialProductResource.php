<?php

namespace App\Http\Resources\SpecialProduct;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\SpecialProduct */
class SpecialProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'id' => $this->id,

            'product_id' => $this->product_id,
            'homepage' => $this->homepage,

            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
