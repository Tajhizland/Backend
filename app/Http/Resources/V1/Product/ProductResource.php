<?php

namespace App\Http\Resources\V1\Product;

use App\Http\Resources\V1\ProductColor\ProductColorCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $minPrice = $this->productColors->map(function ($color) {
            return $color->price;
        })->min("price");

        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'status' => $this->status,
            'view' => $this->view,
            'colors' => new ProductColorCollection($this->activeProductColors),
            'description' => $this->description,
            'min_price' => $this->getMinColorPrice(),
            'study' => $this->study,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
