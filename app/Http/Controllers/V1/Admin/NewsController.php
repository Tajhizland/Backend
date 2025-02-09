<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\News\StoreNewsRequest;
use App\Http\Requests\V1\Admin\News\UpdateNewsRequest;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\News\NewsResource;
use App\Services\New\NewServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class NewsController extends Controller
{

    public function __construct
    (
        private NewServiceInterface $newService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new NewsCollection($this->newService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new NewsResource($this->newService->findById($id)));
    }

    public function store(StoreNewsRequest $request)
    {
        $userId = Auth::user()->id;
        $this->newService->storeNews($request->get("title"), $request->get("url"), $request->get("content"), $request->get("image"), $request->get("published"), $request->get("categoryId"),$userId);
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.news")]));
    }

    public function update(UpdateNewsRequest $request)
    {
        $this->newService->updateNews($request->get("id"), $request->get("title"), $request->get("url"), $request->get("content"), $request->get("image"), $request->get("published"), $request->get("categoryId"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.news")]));
    }
}
