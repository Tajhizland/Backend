<?php

namespace App\Services\Search;

use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Vlog\VlogCollection;
use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Vlog\VlogRepositoryInterface;

class SearchService implements SearchServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private VlogRepositoryInterface    $vlogRepository,
    )
    {
    }

    public function searchQuery($query)
    {
        $products = $this->productRepository->search($query);
        $vlogs = $this->vlogRepository->searchQuery($query);
        return ["products" => ProductCollection::make($products), "vlogs" => VlogCollection::make($vlogs)];
    }

    public function searchPaginate($query)
    {
        return $this->productRepository->searchPaginate($query);
    }

}
