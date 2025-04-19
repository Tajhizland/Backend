<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\PopularProduct\PopularProductCollection;
use App\Http\Resources\V1\Price\PriceResource;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Category\CategoryServiceInterface;
use App\Services\PopularProduct\PopularProductServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface        $productService,
        private BannerServiceInterface         $bannerService,
        private PriceRepositoryInterface       $priceRepository,
        private PopularProductServiceInterface $popularProductService,
        private CategoryServiceInterface       $categoryService,

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

    public function getDiscountedProducts(Request $request)
    {
        $banners = new BannerCollection($this->bannerService->getDiscountedBanner());
        $data = new ProductCollection($this->productService->getDiscountedProducts($request->get("filter")));
        $discounts = new PopularProductCollection($this->popularProductService->get());
        $discountTimer = new PriceResource($this->priceRepository->findFirstExpireDiscount());
        $category = new CategoryCollection($this->categoryService->getDiscountedCategory());
        return $this->dataResponse(
            [
                "data" => $data,
                "discounts" => $discounts,
                "discountTimer" => $discountTimer,
                "category" => $category,
                "banner" => $banners
            ]
        );
    }
}
