<?php

namespace App\Http\Resources\V1\OrderInfo;

use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\Province\ProvinceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\OrderInfo */
class OrderInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'tell' => $this->tell,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'city'=>new CityResource($this->whenLoaded("city")),
            'province'=>new ProvinceResource($this->whenLoaded("province")),
        ];
    }
}
