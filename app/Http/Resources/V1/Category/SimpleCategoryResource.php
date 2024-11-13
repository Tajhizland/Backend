<?php

namespace App\Http\Resources\V1\Category;

use App\Http\Resources\V1\Filter\FilterResource;
use App\Http\Resources\V1\Product\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Category */
class SimpleCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'url' => $this->url,
            'image' => $this->image,
            'parent_id' => $this->parent_id,
            'description' => $this->description,
            'display_name' => $this->pivot->display ?? null,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
