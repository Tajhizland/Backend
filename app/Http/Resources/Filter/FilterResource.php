<?php

namespace App\Http\Resources\Filter;

use App\Http\Resources\FilterItem\FilterItemCollection;
use App\Http\Resources\ProductFilter\ProductFilterCollection;
use App\Http\Resources\ProductFilter\ProductFilterResource;
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
            'status' => $this->status->value,
//            'type' => $this->type->label(),
            'items' => new FilterItemCollection($this->items),
            'productFilters' => new ProductFilterResource($this->whenLoaded('productFilters')),

        ];
    }
}
