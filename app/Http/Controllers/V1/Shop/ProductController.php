<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface $productService,
        private BannerServiceInterface  $bannerService,
    )
    {
    }

    public function find(Request $request)
    {
        $productResponse = $this->productService->findProductByUrl($request->url);
        $relatedProductResponse = $this->productService->getRelatedProducts($productResponse->id);
        return $this->dataResponse([
            "product" => new ProductResource($productResponse),
            "relatedProduct" => new ProductCollection($relatedProductResponse),
        ]);
    }

    public function getDiscountedProducts()
    {
        $banners = new BannerCollection($this->bannerService->getDiscountedBanner());
        $data = new ProductCollection($this->productService->getDiscountedProducts());
        return $this->dataResponse(
            [
                "data" => $data,
                "banner" => $banners
            ]
        );
    }
}
