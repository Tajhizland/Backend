<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Product\ComparisonRequest;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;

class ComparisonController extends Controller
{
    public function __construct(private  ProductServiceInterface $productService)
    {
    }

    public function searchProduct(ComparisonRequest $request)
    {
        return $this->dataResponse(new ProductCollection($this->productService->searchProductWithCategory($request->get("query"),$request->get("category_id"))));
    }

    public function selectProduct($id)
    {
        return $this->dataResponse(new ProductResource($this->productService->findById($id)));
    }
}
