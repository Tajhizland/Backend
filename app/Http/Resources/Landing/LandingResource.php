<?php

namespace App\Http\Resources\Landing;

use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category\SimpleCategoryResource;
use App\Http\Resources\LandingBanner\LandingBannerResource;
use App\Http\Resources\Product\ProductResource;

/** @mixin Landing */
class LandingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'url' => $this->url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'product' =>ProductResource::collection($this->whenLoaded("products")),
            'category' => SimpleCategoryResource::collection($this->whenLoaded("categories")),
            'landingBannerImage' => LandingBannerResource::collection($this->whenLoaded("landingBannerImage")),
            'landingBannerSlider' => LandingBannerResource::collection($this->whenLoaded("landingBannerSlider")),


        ];
    }
}
