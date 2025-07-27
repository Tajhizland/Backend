<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;
use App\Services\ProductGroup\ProductGroupServiceInterface;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct
    (
        private ProductGroupServiceInterface $productGroupService,
        private ProductServiceInterface      $productService,
    )
    {
    }

    public function find(Request $request)
    {
        $response = $this->productGroupService->findByUrl($request->url);
        $relatedProductResponse = $this->productService->getRelatedProducts($response->id);
        return $this->dataResponse([
            "product" => new ProductResource($response),
            "relatedProduct" => new ProductCollection($relatedProductResponse),
        ]);
    }
}
