<?php

namespace App\Http\Resources\V1\PopularCategory;

use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Category\SimpleCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\PopularCategory */
class PopularCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),

            'category_id' => $this->category_id,

            'category' => new SimpleCategoryResource($this->whenLoaded('category')),
        ];
    }
}
