<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Compare\GetProductRequest;
use App\Http\Requests\Shop\Compare\SearchProductRequest;
use App\Http\Resources\Product\SimpleProduct\SimpleProductResource;
use App\Services\Compare\CompareServiceInterface;

class CompareController extends Controller
{
    public function __construct
    (
        private readonly CompareServiceInterface $compareService
    )
    {
    }

    public function findProduct($id)
    {
        $response = $this->compareService->findProductCompare($id);
        return $this->dataResponse(new SimpleProductResource($response));
    }

    public function searchProduct(SearchProductRequest $request)
    {
        $response = $this->compareService->searchProductCompare($request->get("query"), $request->get("categoryIds"));
        return $this->dataResponseCollection(SimpleProductResource::collection($response));
    }

    public function getProducts(GetProductRequest $request)
    {
        $response = $this->compareService->getProducts($request->get("categoryIds"));
        return $this->dataResponseCollection(SimpleProductResource::collection($response));
    }
}
