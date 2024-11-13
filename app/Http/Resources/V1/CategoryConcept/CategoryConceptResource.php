<?php

namespace App\Http\Resources\V1\CategoryConcept;

use App\Http\Resources\V1\Category\CategoryResource;
use App\Http\Resources\V1\Category\SimpleCategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\CategoryConcept */
class CategoryConceptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'concept_id' => $this->concept_id,
            'category_id' => $this->category_id,
            'display' => $this->display,

            'category' => new SimpleCategoryResource($this->whenLoaded('category')),
        ];
    }
}
