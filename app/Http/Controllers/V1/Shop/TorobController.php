<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Torob\TorobResource;
use App\Services\Product\ProductServiceInterface;

class TorobController extends Controller
{
    public function __construct
    (
        private ProductServiceInterface $productService
    )
    {
    }

    public function get()
    {
        $response = $this->productService->torobProduct();
        return (TorobResource::collection($response));
    }
}
