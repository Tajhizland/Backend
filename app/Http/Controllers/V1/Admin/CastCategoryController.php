<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CastCategory\StoreCastCategoryRequest;
use App\Http\Requests\V1\Admin\CastCategory\UpdateCastCategoryRequest;
use App\Http\Resources\V1\CastCategory\CastCategoryCollection;
use App\Http\Resources\V1\CastCategory\CastCategoryResource;
use App\Services\CastCategory\CastCategoryService;
use Illuminate\Support\Facades\Lang;

class CastCategoryController extends Controller
{
    public function __construct
    (
        private CastCategoryService $castCategoryService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->castCategoryService->dataTable();
        return $this->dataResponseCollection(new CastCategoryCollection($response));
    }

    public function get()
    {
        $response = $this->castCategoryService->get();
        return $this->dataResponseCollection(new CastCategoryCollection($response));
    }

    public function find($id)
    {
        $response = $this->castCategoryService->find($id);
        return $this->dataResponse(new CastCategoryResource($response));
    }

    public function store(StoreCastCategoryRequest $request)
    {
        $this->castCategoryService->store($request->get("name"), $request->get("status"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.category")]));
    }

    public function update(UpdateCastCategoryRequest $request)
    {
        $this->castCategoryService->update($request->get("id"), $request->get("name"), $request->get("status"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.category")]));
    }
}
