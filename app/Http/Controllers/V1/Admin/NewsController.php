<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\StoreNewsRequest;
use App\Http\Requests\Admin\News\UpdateNewsRequest;
use App\Http\Resources\News\NewsResource;
use App\Services\New\NewServiceInterface;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{

    public function __construct
    (
        private readonly NewServiceInterface $newService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(NewsResource::collection($this->newService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new NewsResource($this->newService->findById($id)));
    }

    public function store(StoreNewsRequest $request)
    {
        $userId = Auth::user()->id;
        $this->newService->storeNews($request->get("title"), $request->get("url"), $request->get("content"), $request->get("image"), $request->get("published"), $request->get("categoryId"),$userId);
        return $this->successResponse(__("action.store", ["attr" => __("attr.news")]));
    }

    public function update(UpdateNewsRequest $request)
    {
        $this->newService->updateNews($request->get("id"), $request->get("title"), $request->get("url"), $request->get("content"), $request->get("image"), $request->get("published"), $request->get("categoryId"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.news")]));
    }
}
