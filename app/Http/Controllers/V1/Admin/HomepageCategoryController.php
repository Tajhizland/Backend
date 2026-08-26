<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\HomepageCategory\HomepageCategoryAddDto;
use App\DTOs\HomepageCategory\HomepageCategorySetIconDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HomepageCategory\HomepageCategoryRequest;
use App\Http\Requests\Admin\HomepageCategory\SetIconRequest;
use App\Http\Resources\HomepageCategory\HomepageCategoryResource;
use App\Services\HomepageCategory\HomepageCategoryServiceInterface;

class HomepageCategoryController extends Controller
{
    public function __construct(
        private readonly HomepageCategoryServiceInterface $homepageCategoryService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(HomepageCategoryResource::collection($this->homepageCategoryService->dataTable()));
    }

    public function store(HomepageCategoryRequest $request)
    {
        $this->homepageCategoryService->add(new HomepageCategoryAddDto(...$request->validated()));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.category"), "to" => __("attr.list")]));
    }

    public function setIcon($id, SetIconRequest $request)
    {
        $this->homepageCategoryService->setIcon(new HomepageCategorySetIconDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }

    public function destroy($id)
    {
        $this->homepageCategoryService->delete($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.category"), "from" => __("attr.list")]));
    }
}
