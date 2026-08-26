<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Search\SearchQueryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Search\SearchRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Product\ProductResource;
use App\Services\Category\CategoryServiceInterface;
use App\Services\Product\ProductServiceInterface;

class SearchController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface  $productService,
        private readonly CategoryServiceInterface $categoryService,
    )
    {
    }

    public function searchProduct(SearchRequest $request)
    {
        $dto = new SearchQueryDto(...$request->validated());
        return $this->dataResponseCollection(ProductResource::collection($this->productService->searchProduct($dto->query)));
    }

    public function searchCategory(SearchRequest $request)
    {
        $dto = new SearchQueryDto(...$request->validated());
        return $this->dataResponseCollection(CategoryResource::collection($this->categoryService->searchCategory($dto->query)));
    }
}
