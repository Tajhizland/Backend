<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\BlogCategory\StoreBlogCategoryRequest;
use App\Http\Requests\V1\Admin\BlogCategory\UpdateBlogCategoryRequest;
use App\Http\Resources\V1\BlogCategory\BlogCategoryCollection;
use App\Http\Resources\V1\BlogCategory\BlogCategoryResource;
use App\Services\BlogCategory\BlogCategoryServiceInterface;
use Illuminate\Support\Facades\Lang;

class BlogCategoryController extends Controller
{
    public function __construct
    (
        private BlogCategoryServiceInterface $blogCategoryService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new BlogCategoryCollection($this->blogCategoryService->dataTable()));
    }

    public function find($id)
    {
        return $this->dataResponse(new BlogCategoryResource($this->blogCategoryService->findById($id)));
    }

    public function create(StoreBlogCategoryRequest $request)
    {
        $this->blogCategoryService->create($request->get("name"), $request->get("status"), $request->get("url"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.category")]));
    }

    public function update(UpdateBlogCategoryRequest $request)
    {
        $this->blogCategoryService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("url"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.category")]));
    }
}
