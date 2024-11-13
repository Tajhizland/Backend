<?php

namespace App\Http\Resources\V1\HomepageCategory;

use App\Http\Resources\V1\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\HomepageCategory */
class HomepageCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
            'id' => $this->id,

            'category_id' => $this->category_id,

            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
