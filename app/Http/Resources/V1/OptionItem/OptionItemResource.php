<?php

namespace App\Http\Resources\V1\OptionItem;

use App\Http\Resources\V1\ProductOption\ProductOptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

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
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),
        ];
    }
}
