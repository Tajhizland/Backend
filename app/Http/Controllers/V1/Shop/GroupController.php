<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Requests\Shop\Group\FindGroupRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductGroup\ProductGroupServiceInterface;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct
    (
        private readonly ProductGroupServiceInterface $productGroupService,
        private readonly ProductServiceInterface      $productService,
    )
    {
    }

    public function find(FindGroupRequest $request)
    {
        $response = $this->productGroupService->findByUrl($request->validated()["url"]);
        $relatedProductResponse = $this->productService->getRelatedProducts($response->id);
        return $this->dataResponse([
            "product" => new ProductResource($response),
            "relatedProduct" => ProductResource::collection($relatedProductResponse)->response()->getData(),
        ]);
    }
}
