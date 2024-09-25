<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Search\SearchRequest;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Product\ProductServiceInterface;

class SearchController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface  $productService,
        private CategoryServiceInterface $categoryService
    )
    {
    }

    public function searchProduct(SearchRequest $request)
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->searchProduct($request->get("query"))));
    }

    public function searchCategory(SearchRequest $request)
    {
        return $this->dataResponseCollection(new CategoryCollection($this->categoryService->searchCategory($request->get("query"))));

    }
}
