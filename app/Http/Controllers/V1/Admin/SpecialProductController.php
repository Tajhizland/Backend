<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\SpecialProduct\SpecialProductAddDto;
use App\DTOs\SpecialProduct\SpecialProductHomepageDto;
use App\DTOs\SpecialProduct\SpecialProductSortDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpecialProduct\ShowHomepageRequest;
use App\Http\Requests\Admin\SpecialProduct\SpecialProductRequest;
use App\Http\Requests\Admin\SpecialProduct\SpecialProductSortRequest;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\SpecialProduct\SpecialProductResource;
use App\Services\Product\ProductServiceInterface;
use App\Services\SpecialProduct\SpecialProductServiceInterface;

class SpecialProductController extends Controller
{
    public function __construct(
        private readonly SpecialProductServiceInterface $specialProductService,
        private readonly ProductServiceInterface        $productService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(SpecialProductResource::collection($this->specialProductService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(ProductResource::collection($this->productService->special()));
    }

    public function store(SpecialProductRequest $request)
    {
        $this->specialProductService->add(new SpecialProductAddDto(...$request->validated()));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.product"), "to" => __("attr.list")]));
    }

    public function homepage($id, ShowHomepageRequest $request)
    {
        $this->specialProductService->showHomepage(new SpecialProductHomepageDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.list")]));
    }

    public function destroy($id)
    {
        $this->specialProductService->delete($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.product"), "from" => __("attr.list")]));
    }

    public function sort(SpecialProductSortRequest $request)
    {
        $this->specialProductService->sort(new SpecialProductSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.product")]));
    }
}
