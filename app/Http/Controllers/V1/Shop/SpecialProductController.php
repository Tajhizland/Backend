<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Banner\BannerResource;

class SpecialProductController extends Controller
{
    public function __construct
    (
        private readonly ProductServiceInterface $productService,
        private readonly BannerServiceInterface  $bannerService,
    )
    {
    }

    public function list()
    {
        $banners = BannerResource::collection($this->bannerService->getSpecialBanner())->response()->getData();
        $data = ProductResource::collection($this->productService->special())->response()->getData();
        return $this->dataResponse(
            [
                "data" => $data,
                "banner" => $banners
            ]
        );
    }
}
