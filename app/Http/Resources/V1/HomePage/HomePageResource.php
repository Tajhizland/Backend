<?php

namespace App\Http\Resources\V1\HomePage;

use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Campaign\CampaignResource;
use App\Http\Resources\V1\Concept\ConceptCollection;
use App\Http\Resources\V1\Discount\DiscountResource;
use App\Http\Resources\V1\DiscountItem\DiscountItemResource;
use App\Http\Resources\V1\HomepageCategory\HomepageCategoryCollection;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\PopularCategory\PopularCategoryCollection;
use App\Http\Resources\V1\PopularProduct\PopularProductCollection;
use App\Http\Resources\V1\Poster\PosterCollection;
use App\Http\Resources\V1\Price\PriceResource;
use App\Http\Resources\V1\Slider\SliderCollection;
use App\Http\Resources\V1\SpecialProduct\SpecialProductCollection;
use App\Http\Resources\V1\Vlog\VlogCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "popularProducts" => new PopularProductCollection($this["popularProducts"]),
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
            "bannersStock" => new BannerCollection($this["bannersStock"]),
            "posters" => new PosterCollection($this["posters"]),
            "specialProducts" => new SpecialProductCollection($this["specialProducts"]),
        ];
    }
}
