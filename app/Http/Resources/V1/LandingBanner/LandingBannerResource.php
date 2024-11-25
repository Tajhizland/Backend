<?php

namespace App\Http\Resources\V1\LandingBanner;

use App\Http\Resources\V1\Landing\LandingResource;
use App\Models\LandingBanner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LandingBanner */
class LandingBannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'url' => $this->url,
            'slider' => $this->slider,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'landing_id' => $this->landing_id,

            'landing' => new LandingResource($this->whenLoaded('landing')),
        ];
    }
}
