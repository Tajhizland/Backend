<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\News\NewsFileRequest;
use App\Http\Requests\V1\Admin\News\StoreNewsRequest;
use App\Http\Requests\V1\Admin\News\UpdateNewsRequest;
use App\Http\Requests\V1\Admin\Product\ProductFileRequest;
use App\Http\Resources\V1\Filemanager\FilemanagerCollection;
use App\Http\Resources\V1\News\NewsCollection;
use App\Http\Resources\V1\News\NewsResource;
use App\Services\FileManager\FileManagerServiceInterface;
use App\Services\New\NewServiceInterface;
use Illuminate\Support\Facades\Lang;

class NewsController extends Controller
{

    public function __construct
    (
        private  NewServiceInterface $newService,
        private  FileManagerServiceInterface $fileManagerService,
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
        $this->newService->storeNews($request->get("title"),$request->get("url"),$request->get("content"),$request->get("image"),$request->get("published"));
        return $this->successResponse(Lang::get("action.store",["attr"=>Lang::get("attr.news")]));
     }

    public function update(UpdateNewsRequest $request)
    {
        $this->newService->updateNews($request->get("id"),$request->get("title"),$request->get("url"),$request->get("content"),$request->get("image"),$request->get("published"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.news")]));
    }

    public function getFiles($id)
    {
        return $this->dataResponseCollection(new FilemanagerCollection($this->fileManagerService->geyByModelId($id, "news")));
    }

    public function setFile(NewsFileRequest $request)
    {
        $this->fileManagerService->upload($request->file("file"), "news", "news", $request->get("news_id"));
        return $this->successResponse(Lang::get("action.upload", ["attr" => Lang::get("attr.file")]));
    }
}
