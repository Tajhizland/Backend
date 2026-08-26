<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpecialProduct\ShowHomepageRequest;
use App\Http\Requests\Admin\SpecialProduct\SpecialProductRequest;
use App\Http\Requests\Admin\SpecialProduct\SpecialProductSortRequest;
use App\Services\Product\ProductServiceInterface;
use App\Services\SpecialProduct\SpecialProductServiceInterface;
use App\Http\Resources\SpecialProduct\SpecialProductResource;
use App\Http\Resources\Product\ProductResource;

class SpecialProductController extends Controller
{
    public function __construct(
        private readonly SpecialProductServiceInterface $specialProductService,
        private readonly ProductServiceInterface        $productService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(SpecialProductResource::collection($this->specialProductService->dataTable()));
    }

    public function add(SpecialProductRequest $request)
    {
        $this->specialProductService->add($request->get("product_id"));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.product"), "to" => __("attr.list")]));
    }

    public function homepage(ShowHomepageRequest $request)
    {
        $this->specialProductService->showHomepage($request->get("id"), $request->get("homepage"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.list")]));
    }

    public function delete($id)
    {
        $this->specialProductService->delete($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.product"), "from" => __("attr.list")]));
    }

    public function list()
    {
        $data = ProductResource::collection($this->productService->special())->response()->getData();
        return $this->dataResponseCollection(ProductResource::collection($data));
    }
    public function sort(SpecialProductSortRequest $request)
    {
        $this->specialProductService->sort($request->get("special"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.product")]));
    }
}
