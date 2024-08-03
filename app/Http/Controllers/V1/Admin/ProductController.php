<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Product\StoreProductRequest;
use App\Http\Requests\V1\Admin\Product\UpdateProductRequest;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\Product\ProductResource;
use App\Services\Product\ProductServiceInterface;

class ProductController extends Controller
{
    public function __construct(private ProductServiceInterface $productService)
    {
    }

    public function getPaginated()
    {
        return $this->dataResponse(new ProductCollection($this->productService->getPaginatedFilterable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new ProductResource($this->productService->findById($id)));
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->storeProduct($request->get("name"),$request->get("url"),$request->get("description"),$request->get("study"),$request->get("categoryId"),$request->get("color"));
    }

    public function update(UpdateProductRequest $request)
    {
        $this->productService->updateProduct($request->get("id"),$request->get("name"),$request->get("url"),$request->get("description"),$request->get("study"),$request->get("categoryId"),$request->get("color"));

    }
}
