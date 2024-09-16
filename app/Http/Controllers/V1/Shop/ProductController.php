<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;

class ProductController extends  Controller
{
    public function __construct(private  ProductServiceInterface $productService)
    {
    }

    public function find(Request $request)
    {
       return $this->dataResponse(new ProductResource($this->productService->findProductByUrl($request->url)));
    }
}
