<?php

namespace App\Services\Search;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class SearchService implements SearchServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository ,
    )
    {
    }

    public function searchQuery($query)
    {
        return $this->productRepository->search($query);
    }
    public function searchPaginate($query)
    {
        return $this->productRepository->searchPaginate($query);
    }

}
