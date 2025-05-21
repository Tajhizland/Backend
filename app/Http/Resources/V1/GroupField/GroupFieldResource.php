<?php

namespace App\Http\Resources\V1\GroupField;

use App\Http\Resources\V1\GroupProduct\GroupProductResource;
use App\Models\GroupField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin GroupField */
class GroupFieldResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'group_product_id' => $this->group_product_id,

            'groupProduct' => new GroupProductResource($this->whenLoaded('groupProduct')),
        ];
    }
}
