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

            'product' =>$this->whenLoaded("products", fn () => ["data" => ProductResource::collection($this->products)]),
            'category' => $this->whenLoaded("categories", fn () => ["data" => SimpleCategoryResource::collection($this->categories)]),
            'landingBannerImage' => $this->whenLoaded("landingBannerImage", fn () => ["data" => LandingBannerResource::collection($this->landingBannerImage)]),
            'landingBannerSlider' => $this->whenLoaded("landingBannerSlider", fn () => ["data" => LandingBannerResource::collection($this->landingBannerSlider)]),


        ];
    }
}
