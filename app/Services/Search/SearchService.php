<?php

namespace App\Services\Search;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Vlog\VlogResource;
use App\Http\Resources\Product\ProductResource;
use App\Services\Marketing\MarketingTrackerServiceInterface;

readonly class SearchService implements SearchServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface  $productRepository,
        private VlogRepositoryInterface     $vlogRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private MarketingTrackerServiceInterface $marketingTrackerService,
    )
    {
    }

    public function searchQuery($query)
    {
        $products = $this->productRepository->search($query);
        $vlogs = $this->vlogRepository->searchQuery($query);
        $categories = $this->categoryRepository->search($query);

        $this->marketingTrackerService->logSearch(
            $query,
            $products->count() + $vlogs->count() + $categories->count(),
            $products->count()
        );

        return [
            "products" => ProductResource::collection($products)->response()->getData(),
            "vlogs" => VlogResource::collection($vlogs)->response()->getData(),
            "categories" => CategoryResource::collection($categories)->response()->getData(),
        ];
    }

    public function searchPaginate($query)
    {
        $products = $this->productRepository->searchPaginate($query);

        // فقط صفحه اول ثبت می‌شود تا ورق‌زدن نتایج، آمار جستجو را باد نکند.
        if ($products->currentPage() === 1) {
            $this->marketingTrackerService->logSearch($query, $products->total(), $products->total());
        }

        return $products;
    }

}
