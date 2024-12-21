<?php

namespace App\Http\Resources\V1\ProductGuaranty;

use App\Http\Resources\V1\Guaranty\GuarantyResource;
use App\Http\Resources\V1\Product\ProductResource;
use App\Models\ProductGuaranty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProductGuaranty */
class ProductGuarantyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product_id' => $this->product_id,
            'guaranty_id' => $this->guaranty_id,

            'product' => new ProductResource($this->whenLoaded('product')),
            'guaranty' => new GuarantyResource($this->whenLoaded('guaranty')),
        ];
    }
}
