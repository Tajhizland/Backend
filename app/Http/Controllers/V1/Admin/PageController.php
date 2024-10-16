<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Page\StorePageRequest;
use App\Http\Requests\V1\Admin\Page\UpdatePageRequest;
use App\Http\Resources\V1\Page\PageCollection;
use App\Http\Resources\V1\Page\PageResource;
use App\Services\Page\PageServiceInterface;
use Illuminate\Support\Facades\Lang;

class PageController extends Controller
{
    public function __construct
    (
        private PageServiceInterface $pageService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new PageCollection($this->pageService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new PageResource($this->pageService->findById($id)));
    }

    public function store(StorePageRequest $request)
    {
        $this->pageService->store($request->get("title"), $request->get("url"), $request->get("image"), $request->get("content"), $request->get("status"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.page")]));
    }

    public function update(UpdatePageRequest $request)
    {
        $this->pageService->update($request->get("id"), $request->get("title"), $request->get("url"), $request->get("image"), $request->get("content"), $request->get("status"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.page")]));
    }

}
