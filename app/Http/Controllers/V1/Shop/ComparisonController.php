<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\Product\ProductComparisonSearchDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Product\ComparisonRequest;
use App\Http\Resources\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;

class ComparisonController extends Controller
{
    public function __construct(private readonly ProductServiceInterface $productService)
    {
    }

    public function searchProduct(ComparisonRequest $request)
    {
        $dto = new ProductComparisonSearchDto(...$request->validated());
        return $this->dataResponse(ProductResource::collection($this->productService->searchProductWithCategory($dto->query, $dto->category_id))->response()->getData());
    }

    public function selectProduct($id)
    {
        return $this->dataResponse(new ProductResource($this->productService->find($id)));
    }
}
