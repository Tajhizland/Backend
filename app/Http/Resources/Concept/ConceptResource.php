<?php

namespace App\Http\Resources\Concept;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\SimpleCategoryCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

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
            'categories' => new SimpleCategoryCollection($this->whenLoaded('categories')),
            'description' => $this->description,
            'status' => $this->status,
            'icon' => $this->icon,
        ];
    }
}
