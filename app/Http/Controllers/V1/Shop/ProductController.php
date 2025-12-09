<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Banner\BannerCollection;
use App\Http\Resources\V1\Breadcrumb\BreadcrumbCollection;
use App\Http\Resources\V1\Campaign\CampaignResource;
use App\Http\Resources\V1\Category\SimpleCategoryCollection;
use App\Http\Resources\V1\DiscountItem\DiscountItemResource;
use App\Http\Resources\V1\PopularProduct\PopularProductCollection;
use App\Http\Resources\V1\Price\PriceResource;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Http\Resources\V1\ProductOption\ProductOptionCollection;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Services\Banner\BannerServiceInterface;
use App\Services\Breadcrumb\BreadcrumbServiceInterface;
use App\Services\Campaign\CampaignServiceInterface;
use App\Services\Category\CategoryServiceInterface;
use App\Services\DiscountItem\DiscountItemServiceInterface;
use App\Services\Option\OptionServiceInterface;
use App\Services\PopularProduct\PopularProductServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

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
                $breadcrumbCollection = new BreadcrumbCollection($breadcrumb);
                $options = $this->optionService->getByProductIdAndCategoryId($productResponse->id, $category->id);
                $optionsCollection = new ProductOptionCollection($options);
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
            "relatedProduct" => new ProductCollection($relatedProductResponse),
        ]);
    }

    public function getDiscountedProducts(Request $request)
    {
        $banners = new BannerCollection($this->bannerService->getDiscountedBanner());
        $data = new ProductCollection($this->productService->getDiscountedProducts($request->get("filter")));
        $discounts = new PopularProductCollection($this->popularProductService->get());
        $discountTimer = new DiscountItemResource($this->discountItemService->findFirstExpireDiscount());
        $category = new SimpleCategoryCollection($this->categoryService->getDiscountedCategory());
        $campaign = $this->campaignService->findActiveCampaign();
        if ($campaign)
            $campaign = new CampaignResource($this->campaignService->findActiveCampaign());

        return $this->dataResponse(
            [
                "data" => $data,
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
        $data = new ProductCollection($response);
        $category = new SimpleCategoryCollection($this->categoryService->getStockProductCategory());

        return $this->dataResponse(
            [
                "data" => $data,
                "category" => $category,
            ]
        );
    }
}
