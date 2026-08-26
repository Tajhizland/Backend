<?php

namespace App\Services\Search;

use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Vlog\VlogCollection;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;

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
            "products" => ProductCollection::make($products),
            "vlogs" => VlogCollection::make($vlogs),
            "categories" => CategoryCollection::make($categories),
        ];
    }

    public function searchPaginate($query)
    {
        return $this->productRepository->searchPaginate($query);
    }

}
