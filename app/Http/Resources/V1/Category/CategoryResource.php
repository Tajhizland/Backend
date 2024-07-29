<?php

namespace App\Http\Resources\V1\Category;

use App\Http\Resources\V1\Filter\FilterCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'url' => $this->url,
            'image' => $this->image,
            'filters' => new FilterCollection($this->filters),
            'products' => new ProductCollection($this->whenLoaded('products')),
            'parent_id' => $this->parent_id,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
