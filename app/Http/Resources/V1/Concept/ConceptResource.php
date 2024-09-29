<?php

namespace App\Http\Resources\V1\Concept;

use App\Http\Resources\V1\Category\CategoryCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\concept */
class ConceptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'title' => $this->title,
            'category' => new CategoryCollection($this->whenLoaded('category')),
            'description' => $this->description,
            'status' => $this->status,
            'image' => $this->image,
        ];
    }
}
