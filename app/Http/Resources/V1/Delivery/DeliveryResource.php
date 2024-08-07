<?php

namespace App\Http\Resources\V1\Delivery;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Delivery */
class DeliveryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'price' => $this->price,
            'logo' => $this->logo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
