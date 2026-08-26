<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\BrandSortRequest;
use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Services\Brand\BrandServiceInterface;

class BrandController extends Controller
{
    public function __construct
    (
        private readonly BrandServiceInterface $brandService,

    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(BrandResource::collection($this->brandService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(BrandResource::collection($this->brandService->list()));
    }

    public function sort(BrandSortRequest $request)
    {
        $this->brandService->sort($request->get("brand"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.brand")]));
    }

    public function findById($id)
    {
        return $this->dataResponse(new BrandResource($this->brandService->findById($id)));
    }

    public function store(StoreBrandRequest $request)
    {
        $this->brandService->storeBrand($request->get("name"), $request->get("url"), $request->get("status"), $request->get("image"), $request->get("banner"), $request->get("description"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.brand")]));
    }

    public function update(UpdateBrandRequest $request)
    {
        $this->brandService->updateBrand($request->get("id"), $request->get("name"), $request->get("url"), $request->get("status"), $request->get("image"), $request->get("banner"), $request->get("description"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.brand")]));
    }

}
