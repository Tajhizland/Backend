<?php

namespace App\Http\Resources\V1\CartItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CartItem */
class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'name' => $this?->product?->name,
                'url' => $this?->product?->url,
                'image' => $this?->product?->images?->first()?->url,
            ],
            'color' => [
                "id" => $this->productColor->id,
                "title" => $this->productColor->color_name,
                "code" => $this->productColor->color_code,
                "price" => $this->productColor->price?->price,
            ],
            'count' => $this->count,
        ];
    }
}
