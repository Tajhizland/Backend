<?php

namespace App\Http\Resources\Landing;

use App\Http\Resources\Category\SimpleCategoryCollection;
use App\Http\Resources\LandingBanner\LandingBannerCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Models\Landing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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

            'product' =>new ProductCollection($this->whenLoaded("products")),
            'category' => new SimpleCategoryCollection($this->whenLoaded("categories")),
            'landingBannerImage' => new LandingBannerCollection($this->whenLoaded("landingBannerImage")),
            'landingBannerSlider' => new LandingBannerCollection($this->whenLoaded("landingBannerSlider")),


        ];
    }
}
