<?php

namespace App\Http\Resources\V1\Stock;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Stock */
class StockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_color_id' => $this->product_color_id,
            'stock' => $this->stock,
             'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
