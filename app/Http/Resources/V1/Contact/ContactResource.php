<?php

namespace App\Http\Resources\V1\Contact;

use App\Http\Resources\V1\City\CityResource;
use App\Http\Resources\V1\Province\ProvinceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;

/** @mixin \App\Models\Contact */
class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mobile' => $this->mobile,
            'message' => $this->message,
            'city_id' => $this->city_id,
            'concept' => $this->concept,
            'province_id' => $this->province_id,
            'city' => new CityResource($this->city),
            'province' => new ProvinceResource($this->province),
            'created_at' => Jalalian::fromDateTime($this->created_at)->format('Y/m/d H:i:s'),
            'updated_at' => Jalalian::fromDateTime($this->updated_at)->format('Y/m/d H:i:s'),

        ];
    }
}
