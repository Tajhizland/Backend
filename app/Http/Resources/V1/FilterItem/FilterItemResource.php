<?php

namespace App\Http\Resources\V1\FilterItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\FilterItem */
class FilterItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'status' => $this->status,
        ];
    }
}
