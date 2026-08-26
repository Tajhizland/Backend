<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Page\PageStoreDto;
use App\DTOs\Page\PageUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Page\StorePageRequest;
use App\Http\Requests\Admin\Page\UpdatePageRequest;
use App\Http\Resources\Page\PageResource;
use App\Services\Page\PageServiceInterface;

class PageController extends Controller
{
    public function __construct(
        private readonly PageServiceInterface $pageService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(PageResource::collection($this->pageService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new PageResource($this->pageService->find($id)));
    }

    public function store(StorePageRequest $request)
    {
        $this->pageService->store(new PageStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.page")]));
    }

    public function update($id, UpdatePageRequest $request)
    {
        $this->pageService->update(new PageUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.page")]));
    }
}
