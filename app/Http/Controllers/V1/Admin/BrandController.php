<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\V1\Admin\Brand\UpdateBrandRequest;
use App\Http\Requests\V1\Admin\News\NewsFileRequest;
use App\Http\Resources\V1\Brand\BrandCollection;
use App\Http\Resources\V1\Brand\BrandResource;
use App\Http\Resources\V1\Filemanager\FilemanagerCollection;
use App\Services\Brand\BrandServiceInterface;
use App\Services\FileManager\FileManagerServiceInterface;
use Illuminate\Support\Facades\Lang;

class BrandController extends Controller
{
    public function __construct
    (
        private BrandServiceInterface       $brandService,
        private FileManagerServiceInterface $fileManagerService,

    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new BrandCollection($this->brandService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(new BrandCollection($this->brandService->list()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new BrandResource($this->brandService->findById($id)));
    }

    public function store(StoreBrandRequest $request)
    {
        $this->brandService->storeBrand($request->get("name"), $request->get("url"), $request->get("status"), $request->get("image"), $request->get("description"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.brand")]));
    }

    public function update(UpdateBrandRequest $request)
    {
        $this->brandService->updateBrand($request->get("id"), $request->get("name"), $request->get("url"), $request->get("status"), $request->get("image"), $request->get("description"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.brand")]));
    }

    public function getFiles($id)
    {
        return $this->dataResponseCollection(new FilemanagerCollection($this->fileManagerService->geyByModelId($id, "category")));
    }

    public function setFile(NewsFileRequest $request)
    {
        $this->fileManagerService->upload($request->file("file"), "brand", "brand", $request->get("brand_id"));
        return $this->successResponse(Lang::get("action.upload", ["attr" => Lang::get("attr.file")]));
    }

}
