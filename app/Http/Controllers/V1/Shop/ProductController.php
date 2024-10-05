<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductServiceInterface $productService)
    {
    }

    public function find(Request $request)
    {
        $productResponse = $this->productService->findProductByUrl($request->url);
        $relatedProductResponse = $this->productService->getRelatedProducts($productResponse);
        return $this->dataResponse([
            "product" => new ProductResource($productResponse),
            "relatedProduct" => new ProductCollection($relatedProductResponse),
        ]);
    }
}
