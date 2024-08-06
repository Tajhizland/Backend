<?php

namespace App\Http\Resources\V1\HomePage;

use App\Http\Resources\V1\Product\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomePageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "homepageMostPopularProducts" => new ProductCollection($this->homepageMostPopularProducts),
            "homepageHasDiscountProducts" => new ProductCollection($this->homepageHasDiscountProducts),
            "homepageNewProducts" => new ProductCollection($this->homepageNewProducts),
            "homepageCustomCategoryProducts" => new ProductCollection($this->homepageCustomCategoryProducts),
        ];
    }
}
