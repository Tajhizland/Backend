<?php

namespace App\Http\Resources\V1\HomePage;

use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Concept\ConceptCollection;
use App\Http\Resources\V1\HomepageCategory\HomepageCategoryCollection;
use App\Http\Resources\V1\PopularCategory\PopularCategoryCollection;
use App\Http\Resources\V1\PopularProduct\PopularProductCollection;
use App\Http\Resources\V1\Product\SimpleProduct\SimpleProductCollection;
use App\Http\Resources\V1\Slider\SliderCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "popularProducts" => new PopularProductCollection($this["popularProducts"]),
            "popularCategories" => new PopularCategoryCollection($this["popularCategories"]),
            "homepageCategories" => new HomepageCategoryCollection($this["homepageCategories"]),
            "sliders" => new SliderCollection($this["sliders"]),
            "concepts" => new ConceptCollection($this["concepts"]),
            "brands" => new BrandCollection($this["brands"]),
            "specialProducts" => new SimpleProductCollection($this["specialProducts"]),
        ];
    }
}
