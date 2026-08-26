<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Brand\BrandListingDto;
use App\Http\Requests\Shop\Brand\BrandListingRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandResource;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Brand\BrandServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\Category\SimpleCategoryResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Banner\BannerResource;

class BrandController extends Controller
{
    public function __construct
    (
        private readonly BrandServiceInterface  $brandService,
        private readonly BannerServiceInterface $bannerService
    )
    {
    }

    public function index(BrandListingRequest $request)
    {
        $dto = new BrandListingDto(...$request->validated());
        $listing = $this->brandService->listing($dto->url, $dto->filter);

        $brandResource = new BrandResource($listing["brand"]);
        $productCollection = ProductResource::collection($listing["products"])->response()->getData();
        $categoryCollection = $listing["categories"] ? SimpleCategoryResource::collection($listing["categories"])->response()->getData() : $listing["categories"];
        $banners = BannerResource::collection($this->bannerService->getBrandBanner())->response()->getData();
        return $this->dataResponse([
            "brand" => $brandResource,
            "banner" => $banners,
            "products" => $productCollection,
            "categories" => $categoryCollection,
        ]);
    }

    public function list()
    {
        $banners = BannerResource::collection($this->bannerService->getBrandBanner())->response()->getData();
        $data = BrandResource::collection($this->brandService->getAllActive())->response()->getData();
        return $this->dataResponse(
            [
                "brand" => $data,
                "banner" => $banners,
            ]
        );
    }
}
