<?php

namespace App\Http\Resources\V1\LandingCategory;

use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Landing\LandingResource;
use App\Models\LandingCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LandingCategory */
class LandingCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'landing_id' => $this->landing_id,
            'category_id' => $this->category_id,

            'landing' => new LandingResource($this->whenLoaded('landing')),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
