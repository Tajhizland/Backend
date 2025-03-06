<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Product\ProductServiceInterface;

class SpecialProductController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface $productService,
        private BannerServiceInterface  $bannerService,
    )
    {
    }

    public function list()
    {
        $banners = new BannerCollection($this->bannerService->getSpecialBanner());
        $data = new ProductCollection($this->productService->special());
        return $this->dataResponse(
            [
                "data" => $data,
                "banner" => $banners
            ]
        );
    }
}
