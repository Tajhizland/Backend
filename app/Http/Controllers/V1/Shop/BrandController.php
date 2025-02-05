<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Brand\BrandResource;
use App\Http\Resources\V1\Category\SimpleCategoryCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Brand\BrandServiceInterface;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct
    (
        private BrandServiceInterface $brandService ,
        private  BannerServiceInterface $bannerService
    )
    {
    }

    public function index(Request $request)
    {
        $listing = $this->brandService->listing($request->get("url"), $request->get("filter"));

        $brandResource = new BrandResource($listing["brand"]);
        $productCollection = new ProductCollection($listing["products"]);
        $categoryCollection = $listing["categories"] ? new SimpleCategoryCollection($listing["categories"]) : $listing["categories"];
        $banners=new BannerCollection($this->bannerService->getBrandBanner());
        return $this->dataResponse([
            "brand" => $brandResource,
            "banner" => $banners,
            "products" => $productCollection,
            "categories" => $categoryCollection,
        ]);
    }

    public function list()
    {
        return $this->dataResponseCollection(new BrandCollection($this->brandService->getAllActive()));
    }
}
