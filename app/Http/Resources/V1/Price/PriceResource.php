<?php

namespace App\Http\Resources\V1\Price;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Price */
class PriceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_color_id' => $this->product_color_id,
            'price' => $this->price,
            'discount' => $this->discount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
