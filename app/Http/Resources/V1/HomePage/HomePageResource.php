<?php

namespace App\Http\Resources\V1\HomePage;

use App\Http\Resources\V1\Product\SimpleProduct\SimpleProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "homepageMostPopularProducts" => new SimpleProductCollection($this->homepageMostPopularProducts),
            "homepageHasDiscountProducts" => new SimpleProductCollection($this->homepageHasDiscountProducts),
            "homepageNewProducts" => new SimpleProductCollection($this->homepageNewProducts),
            "homepageCustomCategoryProducts" => new SimpleProductCollection($this->homepageCustomCategoryProducts),
        ];
    }
}
