<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CastCategory\StoreCastCategoryRequest;
use App\Http\Requests\Admin\CastCategory\UpdateCastCategoryRequest;
use App\Http\Resources\CastCategory\CastCategoryResource;
use App\Services\CastCategory\CastCategoryServiceInterface;
use Illuminate\Support\Facades\Lang;

class CastCategoryController extends Controller
{
    public function __construct
    (
        private CastCategoryServiceInterface $castCategoryService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->castCategoryService->dataTable();
        return $this->dataResponseCollection(CastCategoryResource::collection($response));
    }

    public function get()
    {
        $response = $this->castCategoryService->get();
        return $this->dataResponseCollection(CastCategoryResource::collection($response));
    }

    public function find($id)
    {
        $response = $this->castCategoryService->find($id);
        return $this->dataResponse(new CastCategoryResource($response));
    }

    public function store(StoreCastCategoryRequest $request)
    {
        $this->castCategoryService->store($request->get("name"), $request->get("status"), $request->file("icon"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.category")]));
    }

    public function update(UpdateCastCategoryRequest $request)
    {
        $this->castCategoryService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->file("icon"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.category")]));
    }
}
