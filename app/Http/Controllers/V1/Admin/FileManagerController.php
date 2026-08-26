<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileManager\FileManagerRequest;
use App\Http\Requests\Admin\FileManager\GetFilesRequest;
use App\Services\FileManager\FileManagerServiceInterface;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\Filemanager\FilemanagerResource;

class FileManagerController extends Controller
{
    public function __construct
    (
        private FileManagerServiceInterface $fileManagerService
    )
    {
    }

    public function upload(FileManagerRequest $request)
    {
        $this->fileManagerService->upload($request->file("file") ,   $request->get("model_type"),$request->get("model_id"));
        return $this->successResponse(Lang::get("action.upload", ["attr" => Lang::get("attr.file")]));
    }
    public function remove($id)
    {
        $this->fileManagerService->remove($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.file")]));
    }
    public function get(GetFilesRequest $request)
    {
        return $this->dataResponseCollection(FilemanagerResource::collection($this->fileManagerService->geyByModelId($request->get("model_id"), $request->get("model_type"))));
    }
}
