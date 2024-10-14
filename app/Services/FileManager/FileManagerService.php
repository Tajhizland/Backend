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
        $filePath = $this->s3Service->upload($file, $modelType);
        $this->fileManagerRepository->store($filePath, $modelType, $modelId);
        return true;
    }

    public function remove($id)
    {
        $file = $this->fileManagerRepository->findOrFail($id);
        $filePath = [$file->path, $file->model_type];
        $filePath =join("/" ,$filePath);
        $this->s3Service->remove($filePath);
        $this->fileManagerRepository->delete($file);
        return true;
    }

    public function geyByModelId($modelId, $modelType)
    {
        return $this->fileManagerRepository->geyByModelId($modelId, $modelType);
    }

}
