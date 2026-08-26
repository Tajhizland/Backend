<?php

namespace App\Http\Resources\HomePage;

use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Brand\BrandCollection;
use App\Http\Resources\Campaign\CampaignResource;
use App\Http\Resources\Concept\ConceptCollection;
use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\DiscountItem\DiscountItemResource;
use App\Http\Resources\HomepageCategory\HomepageCategoryCollection;
use App\Http\Resources\News\NewsCollection;
use App\Http\Resources\PopularCategory\PopularCategoryCollection;
use App\Http\Resources\PopularProduct\PopularProductCollection;
use App\Http\Resources\Poster\PosterCollection;
use App\Http\Resources\Price\PriceResource;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Slider\SliderCollection;
use App\Http\Resources\SpecialProduct\SpecialProductCollection;
use App\Http\Resources\Vlog\VlogCollection;
use App\Http\Resources\TrustedBrand\TrustedBrandCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "popularProducts" => new PopularProductCollection($this["popularProducts"]),
            "topDiscountedProducts" => new ProductCollection($this["topDiscountedProducts"]),
            "campaign" => new CampaignResource($this["campaign"]),
            "pending_campaign" => new CampaignResource($this["pending_campaign"]),
            "discount" => new DiscountItemResource($this["discount"]),
//            "popularCategories" => new PopularCategoryCollection($this["popularCategories"]),
            "homepageCategories" => new HomepageCategoryCollection($this["homepageCategories"]),
            "desktopSliders" => new SliderCollection($this["desktopSliders"]),
            "mobileSliders" => new SliderCollection($this["mobileSliders"]),
            "concepts" => new ConceptCollection($this["concepts"]),
            "news" => new NewsCollection($this["news"]),
            "vlogs" => new VlogCollection($this["vlogs"]),
            "brands" => new BrandCollection($this["brands"]),
            "banners" => new BannerCollection($this["banners"]),
            "banners2" => new BannerCollection($this["banners2"]),
            "banners3" => new BannerCollection($this["banners3"]),
            "banners4" => new BannerCollection($this["banners4"]),
            "banners5" => new BannerCollection($this["banners5"]),
            "bannersCast" => new BannerCollection($this["bannersCast"]),
            "bannersStock" => new BannerCollection($this["bannersStock"]),
            "posters" => new PosterCollection($this["posters"]),
            "specialProducts" => new SpecialProductCollection($this["specialProducts"]),
            "trustedBrands" => new TrustedBrandCollection($this["trustedBrands"]),

        ];
    }
}
