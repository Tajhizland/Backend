<?php

namespace App\Http\Resources\V1\Address;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Address */
class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->city_id,
            'province_id' => $this->province_id,
            'user_id' => $this->user_id,
            'tell_code' => $this->tell_code,
            'tell' => $this->tell,
            'mobile' => $this->mobile,
            'zip_code' => $this->zip_code,
            'address' => $this->address,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
