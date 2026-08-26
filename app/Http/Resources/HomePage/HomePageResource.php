<?php

namespace App\Http\Resources\HomePage;

use App\Http\Resources\Campaign\CampaignResource;
use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\DiscountItem\DiscountItemResource;
use App\Http\Resources\Price\PriceResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\PopularCategory\PopularCategoryResource;
use App\Http\Resources\SpecialProduct\SpecialProductResource;
use App\Http\Resources\HomepageCategory\HomepageCategoryResource;
use App\Http\Resources\PopularProduct\PopularProductResource;
use App\Http\Resources\TrustedBrand\TrustedBrandResource;
use App\Http\Resources\Vlog\VlogResource;
use App\Http\Resources\Slider\SliderResource;
use App\Http\Resources\News\NewsResource;
use App\Http\Resources\Poster\PosterResource;
use App\Http\Resources\Concept\ConceptResource;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Banner\BannerResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "popularProducts" => ["data" => PopularProductResource::collection($this["popularProducts"])],
            "topDiscountedProducts" => ["data" => ProductResource::collection($this["topDiscountedProducts"])],
            "campaign" => new CampaignResource($this["campaign"]),
            "pending_campaign" => new CampaignResource($this["pending_campaign"]),
            "discount" => new DiscountItemResource($this["discount"]),
//            "popularCategories" => ["data" => PopularCategoryResource::collection($this["popularCategories"])],
            "homepageCategories" => ["data" => HomepageCategoryResource::collection($this["homepageCategories"])],
            "desktopSliders" => ["data" => SliderResource::collection($this["desktopSliders"])],
            "mobileSliders" => ["data" => SliderResource::collection($this["mobileSliders"])],
            "concepts" => ["data" => ConceptResource::collection($this["concepts"])],
            "news" => ["data" => NewsResource::collection($this["news"])],
            "vlogs" => ["data" => VlogResource::collection($this["vlogs"])],
            "brands" => ["data" => BrandResource::collection($this["brands"])],
            "banners" => ["data" => BannerResource::collection($this["banners"])],
            "banners2" => ["data" => BannerResource::collection($this["banners2"])],
            "banners3" => ["data" => BannerResource::collection($this["banners3"])],
            "banners4" => ["data" => BannerResource::collection($this["banners4"])],
            "banners5" => ["data" => BannerResource::collection($this["banners5"])],
            "bannersCast" => ["data" => BannerResource::collection($this["bannersCast"])],
            "bannersStock" => ["data" => BannerResource::collection($this["bannersStock"])],
            "posters" => ["data" => PosterResource::collection($this["posters"])],
            "specialProducts" => ["data" => SpecialProductResource::collection($this["specialProducts"])],
            "trustedBrands" => ["data" => TrustedBrandResource::collection($this["trustedBrands"])],

        ];
    }
}
