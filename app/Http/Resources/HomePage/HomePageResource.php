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
            "popularProducts" => PopularProductResource::collection($this["popularProducts"]),
            "topDiscountedProducts" => ProductResource::collection($this["topDiscountedProducts"]),
            "campaign" => new CampaignResource($this["campaign"]),
            "pending_campaign" => new CampaignResource($this["pending_campaign"]),
            "discount" => new DiscountItemResource($this["discount"]),
//            "popularCategories" => PopularCategoryResource::collection($this["popularCategories"]),
            "homepageCategories" => HomepageCategoryResource::collection($this["homepageCategories"]),
            "desktopSliders" => SliderResource::collection($this["desktopSliders"]),
            "mobileSliders" => SliderResource::collection($this["mobileSliders"]),
            "concepts" => ConceptResource::collection($this["concepts"]),
            "news" => NewsResource::collection($this["news"]),
            "vlogs" => VlogResource::collection($this["vlogs"]),
            "brands" => BrandResource::collection($this["brands"]),
            "banners" => BannerResource::collection($this["banners"]),
            "banners2" => BannerResource::collection($this["banners2"]),
            "banners3" => BannerResource::collection($this["banners3"]),
            "banners4" => BannerResource::collection($this["banners4"]),
            "banners5" => BannerResource::collection($this["banners5"]),
            "bannersCast" => BannerResource::collection($this["bannersCast"]),
            "bannersStock" => BannerResource::collection($this["bannersStock"]),
            "posters" => PosterResource::collection($this["posters"]),
            "specialProducts" => SpecialProductResource::collection($this["specialProducts"]),
            "trustedBrands" => TrustedBrandResource::collection($this["trustedBrands"]),

        ];
    }
}
