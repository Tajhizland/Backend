<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Compare\SearchProductRequest;
use App\Http\Resources\V1\Product\SimpleProduct\SimpleProductCollection;
use App\Http\Resources\V1\Product\SimpleProduct\SimpleProductResource;
use App\Services\Compare\CompareServiceInterface;

class CompareController extends Controller
{
    public function __construct
    (
        private CompareServiceInterface $compareService
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
        return $this->dataResponseCollection(new SimpleProductCollection($response));
    }
}
