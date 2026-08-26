<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Campaign\CampaignResource;
use App\Http\Resources\DiscountItem\DiscountItemResource;
use App\Http\Resources\Price\PriceResource;
use App\Http\Resources\Product\ProductResource;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Breadcrumb\BreadcrumbServiceInterface;
use App\Services\Campaign\CampaignServiceInterface;
use App\Services\Category\CategoryServiceInterface;
use App\Services\DiscountItem\DiscountItemServiceInterface;
use App\Services\Option\OptionServiceInterface;
use App\Services\PopularProduct\PopularProductServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;
use App\Http\Resources\Category\SimpleCategoryResource;
use App\Http\Resources\Breadcrumb\BreadcrumbResource;
use App\Http\Resources\PopularProduct\PopularProductResource;
use App\Http\Resources\ProductOption\ProductOptionResource;
use App\Http\Resources\Banner\BannerResource;

class ProductController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface        $productService,
        private OptionServiceInterface         $optionService,
        private BannerServiceInterface         $bannerService,
        private PriceRepositoryInterface       $priceRepository,
        private PopularProductServiceInterface $popularProductService,
        private CategoryServiceInterface       $categoryService,
        private BreadcrumbServiceInterface     $breadcrumbService,
        private CampaignServiceInterface       $campaignService,
        private DiscountItemServiceInterface   $discountItemService,
        private ProductRepositoryInterface     $productRepository,

    )
    {
    }

    public function find(Request $request)
    {
        $productResponse = $this->productService->findProductByUrl($request->url);
        $relatedProductResponse = $this->productService->getRelatedProducts($productResponse->id);
        $breadcrumbCollection = [];
        $options = [];
        if ($productResponse) {
            $category = $productResponse->categories[0];
            if ($category) {
                $breadcrumb = $this->breadcrumbService->generate($category);
                $breadcrumbCollection = BreadcrumbResource::collection($breadcrumb)->response()->getData();
                $options = $this->optionService->getByProductIdAndCategoryId($productResponse->id, $category->id);
                $optionsCollection = ProductOptionResource::collection($options)->response()->getData();
            }
        }
        $campaign = $this->campaignService->findActiveCampaign();
        if ($campaign)
            $campaign = new CampaignResource($this->campaignService->findActiveCampaign());
        return $this->dataResponse([
            "product" => new ProductResource($productResponse),
            "breadcrumb" => $breadcrumbCollection,
            "options" => $optionsCollection,
            "campaign" => $campaign,
            "relatedProduct" => ProductResource::collection($relatedProductResponse)->response()->getData(),
        ]);
    }

    public function getDiscountedProducts(Request $request)
    {
        $banners = BannerResource::collection($this->bannerService->getDiscountedBanner())->response()->getData();
        $data = ProductResource::collection($this->productService->getDiscountedProducts($request->get("filter")))->response()->getData();
        $discounts = PopularProductResource::collection($this->popularProductService->get())->response()->getData();

        $category = SimpleCategoryResource::collection($this->categoryService->getDiscountedCategory())->response()->getData();

        $discountTimer = $this->discountItemService->findFirstExpireDiscount();
        if ($discountTimer)
            $discountTimer = new DiscountItemResource($discountTimer);

        $campaign = $this->campaignService->findActiveCampaign();
        if ($campaign)
            $campaign = new CampaignResource($this->campaignService->findActiveCampaign());

        $discountedProducts = $this->productRepository->getTopDiscountedProducts();
        if ($discountedProducts) {
            $discountedProducts = ProductResource::collection($discountedProducts)->response()->getData();
        }
        return $this->dataResponse(
            [
                "data" => $data,
                "topDiscountedProducts" => $discountedProducts,
                "campaign" => $campaign,
                "discounts" => $discounts,
                "discountTimer" => $discountTimer,
                "category" => $category,
                "banner" => $banners
            ]
        );
    }

    public function getStockProducts(Request $request)
    {
        $response = $this->productService->getStockProducts($request->get("filter"));
        $data = ProductResource::collection($response)->response()->getData();
        $category = SimpleCategoryResource::collection($this->categoryService->getStockProductCategory())->response()->getData();

        return $this->dataResponse(
            [
                "data" => $data,
                "category" => $category,
            ]
        );
    }
}
