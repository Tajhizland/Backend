<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\V1\Admin\Brand\UpdateBrandRequest;
use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Brand\BrandResource;
use App\Services\Brand\BrandServiceInterface;
use Illuminate\Support\Facades\Lang;

class BrandController extends Controller
{
    public function __construct
    (
        private  BrandServiceInterface $brandService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponse(new BrandCollection($this->brandService->dataTable()));
    }
    public function list()
    {
        return $this->dataResponse(new BrandCollection($this->brandService->list()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new BrandResource($this->brandService->findById($id)));
    }

    public function store(StoreBrandRequest $request)
    {
        $this->brandService->storeBrand($request->get("name"),$request->get("url"),$request->get("status"),$request->get("image"),$request->get("description"));
        return $this->successResponse(Lang::get("action.store",["attr"=>Lang::get("attr.brand")]));
    }

    public function update(UpdateBrandRequest $request)
    {
        $this->brandService->updateBrand($request->get("id"),$request->get("name"),$request->get("url"),$request->get("status"),$request->get("image"),$request->get("description"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.brand")]));
    }
}
