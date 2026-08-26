<?php

namespace App\Http\Resources\Concept;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use App\Http\Resources\Category\SimpleCategoryResource;

/** @mixin \App\Models\concept */
class ConceptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'id' => $this->id,
            'title' => $this->title,
            'categories' => SimpleCategoryResource::collection($this->whenLoaded('categories')),
            'description' => $this->description,
            'status' => $this->status,
            'icon' => $this->icon,
        ];
    }
}
