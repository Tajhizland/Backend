<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageCategory\HomepageCategoryRequest;
use App\Http\Requests\Admin\HomepageCategory\SetIconRequest;
use App\Services\HomepageCategory\HomepageCategoryServiceInterface;
use App\Http\Resources\HomepageCategory\HomepageCategoryResource;

class HomepageCategoryController extends Controller
{
    public function __construct(
        private readonly HomepageCategoryServiceInterface $homepageCategoryService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(HomepageCategoryResource::collection($this->homepageCategoryService->dataTable()));
    }

    public function add(HomepageCategoryRequest $request)
    {
        $this->homepageCategoryService->add($request->get("category_id"));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.category"), "to" => __("attr.list")]));
    }

    public function delete($id)
    {
        $this->homepageCategoryService->delete($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.category"), "from" => __("attr.list")]));
    }
    public function setIcon(SetIconRequest $request)
    {
        $this->homepageCategoryService->setIcon($request->get("id"),$request->file("icon"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }
}
