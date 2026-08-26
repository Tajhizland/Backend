<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\BlogCategory\BlogCategoryStoreDto;
use App\DTOs\BlogCategory\BlogCategoryUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategory\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\BlogCategory\UpdateBlogCategoryRequest;
use App\Http\Resources\BlogCategory\BlogCategoryResource;
use App\Services\BlogCategory\BlogCategoryServiceInterface;

class BlogCategoryController extends Controller
{
    public function __construct(
        private readonly BlogCategoryServiceInterface $blogCategoryService,
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

    public function show($id)
    {
        return $this->dataResponse(new BlogCategoryResource($this->blogCategoryService->find($id)));
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        $this->blogCategoryService->store(new BlogCategoryStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.category")]));
    }

    public function update($id, UpdateBlogCategoryRequest $request)
    {
        $this->blogCategoryService->update(new BlogCategoryUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.category")]));
    }
}
