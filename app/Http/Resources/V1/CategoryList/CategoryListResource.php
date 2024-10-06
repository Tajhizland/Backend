<?php

namespace App\Http\Resources\V1\CategoryList;

use App\Http\Resources\V1\Filter\FilterCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Category */
class CategoryListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
