<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Product\FindProductRequest;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;

class ProductController extends  Controller
{
    public function __construct(private  ProductServiceInterface $productService)
    {
    }

    public function find(FindProductRequest $request)
    {
       return $this->dataResponse(new ProductResource($this->productService->findProductByUrl($request->get("url"))));
    }

    public function findAll(FindProductRequest $request)
    {
       return $this->dataResponse(new ProductResource($this->productService->findProductByUrl($request->get("url"))));
    }
}
