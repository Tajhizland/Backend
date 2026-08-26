<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\FileManager\FileManagerGetDto;
use App\DTOs\FileManager\FileManagerUploadDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FileManager\FileManagerRequest;
use App\Http\Requests\Admin\FileManager\GetFilesRequest;
use App\Http\Resources\Filemanager\FilemanagerResource;
use App\Services\FileManager\FileManagerServiceInterface;

class FileManagerController extends Controller
{
    public function __construct(
        private readonly FileManagerServiceInterface $fileManagerService,
    )
    {
    }

    public function store(FileManagerRequest $request)
    {
        $this->fileManagerService->upload(new FileManagerUploadDto(...$request->validated()));
        return $this->successResponse(__("action.upload", ["attr" => __("attr.file")]));
    }

    public function index(GetFilesRequest $request)
    {
        $dto = new FileManagerGetDto(...$request->validated());
        return $this->dataResponseCollection(FilemanagerResource::collection($this->fileManagerService->getByModel($dto)));
    }

    public function destroy($id)
    {
        $this->fileManagerService->remove($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.file")]));
    }
}
