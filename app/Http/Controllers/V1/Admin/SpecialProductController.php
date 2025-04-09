<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\SpecialProduct\ShowHomepageRequest;
use App\Http\Requests\V1\Admin\SpecialProduct\SpecialProductRequest;
use App\Http\Requests\V1\Admin\SpecialProduct\SpecialProductSortRequest;
use App\Http\Resources\V1\Product\ProductCollection;
use App\Http\Resources\V1\SpecialProduct\SpecialProductCollection;
use App\Services\Product\ProductServiceInterface;
use App\Services\SpecialProduct\SpecialProductServiceInterface;
use Illuminate\Support\Facades\Lang;

class SpecialProductController extends Controller
{
    public function __construct(
        private SpecialProductServiceInterface $specialProductService,
        private ProductServiceInterface        $productService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new SpecialProductCollection($this->specialProductService->dataTable()));
    }

    public function add(SpecialProductRequest $request)
    {
        $this->specialProductService->add($request->get("product_id"));
        return $this->successResponse(Lang::get("action.add_to", ["attr" => Lang::get("attr.product"), "to" => Lang::get("attr.list")]));
    }

    public function homepage(ShowHomepageRequest $request)
    {
        $this->specialProductService->showHomepage($request->get("id"), $request->get("homepage"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.list")]));
    }

    public function delete($id)
    {
        $this->specialProductService->delete($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.product"), "from" => Lang::get("attr.list")]));
    }

    public function list()
    {
        $data = new ProductCollection($this->productService->special());
        return $this->dataResponseCollection(new ProductCollection($data));
    }
    public function sort(SpecialProductSortRequest $request)
    {
        $this->specialProductService->sort($request->get("special"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.product")]));
    }
}
