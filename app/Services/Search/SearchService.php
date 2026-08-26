<?php

namespace App\Services\Search;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Vlog\VlogResource;
use App\Http\Resources\Product\ProductResource;

class SearchService implements SearchServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface  $productRepository,
        private VlogRepositoryInterface     $vlogRepository,
        private CategoryRepositoryInterface $categoryRepository,
    )
    {
    }

    public function searchQuery($query)
    {
        $products = $this->productRepository->search($query);
        $vlogs = $this->vlogRepository->searchQuery($query);
        $categories = $this->categoryRepository->search($query);
        return [
            "products" => ProductResource::collection($products)->response()->getData(),
            "vlogs" => VlogResource::collection($vlogs)->response()->getData(),
            "categories" => CategoryResource::collection($categories)->response()->getData(),
        ];
    }

    public function searchPaginate($query)
    {
        return $this->productRepository->searchPaginate($query);
    }

}
