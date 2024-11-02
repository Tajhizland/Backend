<?php

namespace App\Services\FileManager;

use App\Repositories\FileManager\FileManagerRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class FileManagerService implements FileManagerServiceInterface
{
    public function __construct
    (
        private FileManagerRepositoryInterface $fileManagerRepository,
        private S3ServiceInterface             $s3Service,
    )
    {
    }

    public function upload($file, $modelType, $modelId)
    {
        $path = [$modelType, "file"];
        $path = join("/", $path);
        $filePath = $this->s3Service->upload($file, $path);
        return $this->fileManagerRepository->create([
            "path" => $filePath,
            "model_type" => $modelType,
            "model_id" => $modelId
        ]);
    }

    public function remove($id)
    {
        $file = $this->fileManagerRepository->findOrFail($id);
        $filePath = [$file->model_type, "file", $file->path];
        $filePath = join("/", $filePath);
        $this->s3Service->remove($filePath);
        $this->fileManagerRepository->delete($file);
        return true;
    }

    public function geyByModelId($modelId, $modelType)
    {
        return $this->fileManagerRepository->geyByModelId($modelId, $modelType);
    }

}
