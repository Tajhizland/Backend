<?php

namespace App\Http\Resources\V1\GroupFieldValue;

use App\Http\Resources\V1\GroupField\GroupFieldResource;
use App\Http\Resources\V1\GroupProduct\GroupProductResource;
 use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupFieldValueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'group_product_id' => $this->group_product_id,
            'group_field_id' => $this->group_field_id,

            'groupProduct' => new GroupProductResource($this->whenLoaded('groupProduct')),
            'groupField' => new GroupFieldResource($this->whenLoaded('groupField')),
        ];
    }
}
