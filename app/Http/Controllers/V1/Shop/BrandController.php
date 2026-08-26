<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerCollection;
use App\Http\Resources\Brand\BrandCollection;
use App\Http\Resources\Brand\BrandResource;
use App\Http\Resources\Category\SimpleCategoryCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Brand\BrandServiceInterface;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct
    (
        private BrandServiceInterface  $brandService,
        private BannerServiceInterface $bannerService
    )
    {
    }

    public function index(Request $request)
    {
        $listing = $this->brandService->listing($request->get("url"), $request->get("filter"));

        $brandResource = new BrandResource($listing["brand"]);
        $productCollection = new ProductCollection($listing["products"]);
        $categoryCollection = $listing["categories"] ? new SimpleCategoryCollection($listing["categories"]) : $listing["categories"];
        $banners = new BannerCollection($this->bannerService->getBrandBanner());
        return $this->dataResponse([
            "brand" => $brandResource,
            "banner" => $banners,
            "products" => $productCollection,
            "categories" => $categoryCollection,
        ]);
    }

    public function list()
    {
        $banners = new BannerCollection($this->bannerService->getBrandBanner());
        $data = new BrandCollection($this->brandService->getAllActive());
        return $this->dataResponse(
            [
                "brand" => $data,
                "banner" => $banners,
            ]
        );
    }
}
