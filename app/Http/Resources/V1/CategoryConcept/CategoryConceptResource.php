<?php

namespace App\Http\Resources\V1\CategoryConcept;

use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Category\SimpleCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CategoryConcept */
class CategoryConceptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'concept_id' => $this->concept_id,
            'category_id' => $this->category_id,
            'display' => $this->display,

            'category' => new SimpleCategoryResource($this->whenLoaded('category')),
        ];
    }
}
