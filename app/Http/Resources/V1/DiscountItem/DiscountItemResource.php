<?php

namespace App\Http\Resources\V1\DiscountItem;

use App\Http\Resources\DiscountResource;
use App\Http\Resources\V1\ProductColor\ProductColorResource;
use App\Models\DiscountItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin DiscountItem */
class DiscountItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'discount' => $this->discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'discount_id' => $this->discount_id,
            'product_color_id' => $this->product_color_id,

//            'discount' => new DiscountResource($this->whenLoaded('discount')),
            'productColor' => new ProductColorResource($this->whenLoaded('productColor')),
        ];
    }
}
