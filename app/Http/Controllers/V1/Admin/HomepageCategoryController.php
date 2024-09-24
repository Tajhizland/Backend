<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\HomepageCategory\HomepageCategoryRequest;
use App\Http\Resources\V1\HomepageCategory\HomepageCategoryCollection;
use App\Services\HomepageCategory\HomepageCategoryServiceInterface;
use Illuminate\Support\Facades\Lang;

class HomepageCategoryController extends Controller
{
    public function __construct(
        private HomepageCategoryServiceInterface $homepageCategoryService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new HomepageCategoryCollection($this->homepageCategoryService->dataTable()));
    }

    public function add(HomepageCategoryRequest $request)
    {
        $this->homepageCategoryService->add($request->get("category_id"));
        return $this->successResponse(Lang::get("action.add_to", ["attr" => Lang::get("attr.category"), "to" => Lang::get("attr.list")]));
    }

    public function delete($id)
    {
        $this->homepageCategoryService->delete($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.category"), "from" => Lang::get("attr.list")]));
    }
}
