<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\Product\ProductCollection;
use App\Services\Product\ProductServiceInterface;

class ProductController extends  Controller
{
    public function __construct(private  ProductServiceInterface $productService)
    {
    }
    public function getPaginated()
    {
         return $this->dataResponse(new ProductCollection($this->productService->getPaginatedFilterable()));
    }
}
