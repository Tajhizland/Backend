<?php

namespace App\Http\Resources;

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
                'name' => $this->product->title,
                'url' => $this->product->url,
            ],
            'color' => [
                "title" => $this->productColor->color_name,
                "code" => $this->productColor->color_code,
                "price" => $this->productColor->price?->price,
            ],
            'count' => $this->count,
        ];
    }
}
