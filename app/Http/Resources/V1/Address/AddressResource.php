<?php

namespace App\Http\Resources\V1\Address;

use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\Province\ProvinceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Address */
class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->city_id,
            'title' => $this->title,
            'province_id' => $this->province_id,
            'user_id' => $this->user_id,
            'tell' => $this->tell,
            'mobile' => $this->mobile,
            'zip_code' => $this->zip_code,
            'address' => $this->address,
            'active' => $this->active,
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),

            'city' => new CityResource($this->whenLoaded("city")),
            'province' => new ProvinceResource($this->whenLoaded("province")),
        ];
    }
}
