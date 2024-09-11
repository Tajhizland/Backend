<?php

namespace App\Http\Resources\V1\Filter;

use App\Http\Resources\V1\FilterItem\FilterItemCollection;
use App\Http\Resources\V1\ProductFilter\ProductFilterCollection;
use App\Http\Resources\V1\ProductFilter\ProductFilterResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Filter */
class FilterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->label(),
            'items' => new FilterItemCollection($this->items),
            'productFilters' => new ProductFilterResource($this->whenLoaded('productFilters')),

        ];
    }
}
