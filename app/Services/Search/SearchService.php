<?php

namespace App\Services\Search;

use App\Repositories\Category\CategoryRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;

class SearchService implements SearchServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository ,
        private CategoryRepositoryInterface $categoryRepository ,
    )
    {
    }

    public function searchQuery($query)
    {
        $products=$this->productRepository->search($query);
        $category=$this->categoryRepository->search($query);
        $data=[
            "products"=>$products,
            "categories"=>$category
        ];
        return $data;
    }

}
