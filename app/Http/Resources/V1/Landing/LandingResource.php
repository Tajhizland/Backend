<?php

namespace App\Http\Resources\V1\Landing;

use App\Http\Resources\V1\Category\SimpleCategoryCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Landing */
class LandingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product' =>new ProductCollection($this->whenLoaded("products")),
            'category' => new SimpleCategoryCollection($this->whenLoaded("categories")),


        ];
    }
}
