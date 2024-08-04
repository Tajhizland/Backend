<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Filter\StoreFilterRequest;
use App\Http\Requests\V1\Admin\Filter\UpdateFilterRequest;
use App\Http\Resources\V1\Filter\FilterCollection;
use App\Http\Resources\V1\Filter\FilterResource;
use App\Services\Filter\FilterServiceInterface;
use Illuminate\Support\Facades\Lang;

class FilterController extends Controller
{
    public function __construct
    (
        private  FilterServiceInterface $filterService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponse(new FilterCollection($this->filterService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new FilterResource($this->filterService->findById($id)));
    }

    public function store(StoreFilterRequest $request)
    {
        $this->filterService->createFilter($request->get("name"),$request->get("category_id"),$request->get("status"),$request->get("type"),$request->get("items"));
        return $this->successResponse(Lang::get("responses.filter_store_success"));
    }

    public function update(UpdateFilterRequest $request)
    {
        $this->filterService->updateFilter($request->get("id"),$request->get("name"),$request->get("category_id"),$request->get("status"),$request->get("type"),$request->get("items"));
        return $this->successResponse(Lang::get("responses.filter_update_success"));
    }
}
