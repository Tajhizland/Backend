<?php

namespace App\Http\Resources\V1\VlogCategory;

use App\Models\VlogCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin VlogCategory */
class VlogCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
