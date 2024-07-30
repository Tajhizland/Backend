<?php

namespace App\Http\Resources\V1\ProductColor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ProductColor */
class ProductColorResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'color_name' => $this->color_name,
            'color_code' => $this->color_code,
            'status' => $this->status,
            'price' => $this->price?->price,
            'discount' => $this->price?->discount,
            'stock' => $this->stock?->stock,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
