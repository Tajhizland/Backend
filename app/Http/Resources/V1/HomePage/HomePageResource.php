<?php

namespace App\Http\Resources\V1\HomePage;

use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Concept\ConceptCollection;
use App\Http\Resources\V1\HomepageCategory\HomepageCategoryCollection;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\PopularCategory\PopularCategoryCollection;
use App\Http\Resources\V1\PopularProduct\PopularProductCollection;
use App\Http\Resources\V1\Slider\SliderCollection;
use App\Http\Resources\V1\SpecialProduct\SpecialProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "popularProducts" => new PopularProductCollection($this["popularProducts"]),
//            "popularCategories" => new PopularCategoryCollection($this["popularCategories"]),
            "homepageCategories" => new HomepageCategoryCollection($this["homepageCategories"]),
            "sliders" => new SliderCollection($this["sliders"]),
            "concepts" => new ConceptCollection($this["concepts"]),
            "news" => new NewsCollection($this["news"]),
            "brands" => new BrandCollection($this["brands"]),
            "banners" => new BannerCollection($this["banners"]),
            "specialProducts" => new SpecialProductCollection($this["specialProducts"]),
        ];
    }
}
