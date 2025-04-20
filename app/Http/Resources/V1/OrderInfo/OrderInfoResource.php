<?php

namespace App\Http\Resources\V1\OrderInfo;

use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\Province\ProvinceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

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
            'last_name' => $this->last_name,
            'national_code' => $this->national_code,
            'zip_code' => $this->zip_code,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),

            'city'=>new CityResource($this->whenLoaded("city")),
            'province'=>new ProvinceResource($this->whenLoaded("province")),
        ];
    }
}
