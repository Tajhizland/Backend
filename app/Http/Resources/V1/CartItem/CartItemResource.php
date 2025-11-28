<?php

namespace App\Http\Resources\V1\CartItem;

use App\Enums\ProductColorStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CartItem */
class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price = $this->productColor?->price?->price;
        $discountItem = $this->productColor->discountItem->first();

        $hasStock = true;
        if ($this->count > $this->productColor->stock->stock || $this->productColor->status == ProductColorStatus::DeActive->value) {
            $hasStock = false;
        }
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
                "status" => $this->productColor->status,
                "delivery_delay" => $this->productColor->delivery_delay,
                "price" => $this->productColor->price?->price,
                "discountedPrice" => $discountItem?->discount_price,
                "discount" => $this->productColor?->price?->price - ($this->productColor?->price?->price * ($discountItem?->discount_price / 100))
            ],
            'guaranty' => [
                'id' => $this->guaranty?->id,
                'name' => $this->guaranty?->name,
                'free' => $this->guaranty?->free,
            ],
            'count' => $this->count,
            'hasStock' => $hasStock,
        ];
    }
}
