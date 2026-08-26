<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Filter\FilterResource;

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
            'parent_id' => $this->parent_id,
            'type' => $this->type,
            'description' => $this->description,
             'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
//            'minPrice'=> $this->getMinProductPrice(),
//            'maxPrice'=> $this->getMaxProductPrice(),
            'filters' => FilterResource::collection($this->whenLoaded('filters')),
            'products' => ProductResource::collection($this->whenLoaded('products')),

        ];
    }
}
