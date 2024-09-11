<?php

namespace App\Http\Resources\V1\OptionItem;

use App\Http\Resources\V1\ProductOption\ProductOptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OptionItem */
class OptionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'option_id' => $this->option_id,
            'title' => $this->title,
            'status' => $this->status,
            'productOption' => new ProductOptionResource($this->whenLoaded('productOption')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
