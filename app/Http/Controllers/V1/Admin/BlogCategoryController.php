<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategory\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\BlogCategory\UpdateBlogCategoryRequest;
use App\Http\Resources\BlogCategory\BlogCategoryResource;
use App\Services\BlogCategory\BlogCategoryServiceInterface;

class BlogCategoryController extends Controller
{
    public function __construct
    (
        private readonly BlogCategoryServiceInterface $blogCategoryService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(BlogCategoryResource::collection($this->blogCategoryService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(BlogCategoryResource::collection($this->blogCategoryService->list()));
    }


    public function findById($id)
    {
        return $this->dataResponse(new BlogCategoryResource($this->blogCategoryService->findById($id)));
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        $this->blogCategoryService->create($request->get("name"), $request->get("status"), $request->get("url"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.category")]));
    }

    public function update(UpdateBlogCategoryRequest $request)
    {
        $this->blogCategoryService->update($request->get("id"), $request->get("name"), $request->get("status"), $request->get("url"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }
}
