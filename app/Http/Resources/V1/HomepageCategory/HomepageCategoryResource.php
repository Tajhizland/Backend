<?php

namespace App\Http\Resources\V1\HomepageCategory;

use App\Http\Resources\V1\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\HomepageCategory */
class HomepageCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,

            'category_id' => $this->category_id,

            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
