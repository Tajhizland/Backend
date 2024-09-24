<?php

namespace App\Http\Resources\V1\PopularCategory;

use App\Http\Resources\V1\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\PopularCategory */
class PopularCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'category_id' => $this->category_id,

            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
