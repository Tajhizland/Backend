<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Services\Product\ProductServiceInterface;

class SpecialProductController extends Controller
{
    public function __construct
    (
        private  ProductServiceInterface $productService
    )
    {
    }

    public function list()
    {
        return $this->dataResponseCollection(new ProductCollection($this->productService->special()));
    }
}
