<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Option\StoreOptionRequest;
use App\Http\Requests\V1\Admin\Option\UpdateOptionRequest;
use App\Http\Resources\V1\Option\OptionCollection;
use App\Http\Resources\V1\Option\OptionResource;
use App\Services\Option\OptionServiceInterface;
use Illuminate\Support\Facades\Lang;

class OptionController extends Controller
{
    public function __construct
    (
        private  OptionServiceInterface $optionService
    )
    {}
    public function dataTable()
    {
        return $this->dataResponse(new OptionCollection($this->optionService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new OptionResource($this->optionService->findById($id)));
    }

    public function store(StoreOptionRequest $request)
    {
        $this->optionService->createOption($request->get("title"),$request->get("category_id"),$request->get("status") ,$request->get("items"));
        return $this->successResponse(Lang::get("action.store",["attr"=>Lang::get("attr.option")]));
     }

    public function update(UpdateOptionRequest $request)
    {
        $this->optionService->updateOption($request->get("id"),$request->get("title"),$request->get("category_id"),$request->get("status") ,$request->get("items"));
        return $this->successResponse(Lang::get("action.store",["attr"=>Lang::get("attr.news")]));
    }
}
