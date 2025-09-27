<?php

namespace App\Services\Search;

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
        return ["products" => $products, "vlogs" => $vlogs];
    }

    public function searchPaginate($query)
    {
        return $this->productRepository->searchPaginate($query);
    }

}
