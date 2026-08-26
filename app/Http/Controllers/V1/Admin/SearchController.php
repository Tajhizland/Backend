<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Search\SearchRequest;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Product\ProductServiceInterface;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Product\ProductResource;

class SearchController extends Controller
{
    public function __construct
    (
        private readonly ProductServiceInterface  $productService,
        private readonly CategoryServiceInterface $categoryService
    )
    {
    }

    public function searchProduct(SearchRequest $request)
    {
        return $this->dataResponseCollection(ProductResource::collection($this->productService->searchProduct($request->get("query"))));
    }

    public function searchCategory(SearchRequest $request)
    {
        return $this->dataResponseCollection(CategoryResource::collection($this->categoryService->searchCategory($request->get("query"))));

    }
}
