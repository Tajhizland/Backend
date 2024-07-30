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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'status' => $this->status,
            'view' => $this->view,
            'description' => $this->description,
            'category' =>  $this->categories->first()->name??"",
            'min_price' => $this->getMinColorPrice(),
            'study' => $this->study,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'colors' => new ProductColorCollection($this->activeProductColors),

        ];
    }
}
