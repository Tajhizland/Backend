<?php

namespace App\Http\Resources\GroupProduct;

use App\Http\Resources\GroupFieldValue\GroupFieldValueResource;
use App\Http\Resources\Product\ProductResource;
use App\Models\GroupProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GroupProduct */
class
GroupProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'group_id' => $this->group_id,
            'product_id' => $this->product_id,

            'group' => new ProductResource($this->whenLoaded('group')),
            'product' => new ProductResource($this->whenLoaded('product')),
            'value' => GroupFieldValueResource::collection($this->whenLoaded('value')),
        ];
    }
}
