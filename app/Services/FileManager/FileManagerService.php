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

    public function upload($file, $path, $modelType, $modelId)
    {
        $this->s3Service->upload($file, $path);
        $this->fileManagerRepository->store($path, $modelType, $modelId);
        return true;
    }

    public function geyByModelId($modelId, $modelType)
    {
        return $this->fileManagerRepository->geyByModelId($modelId, $modelType);
    }

}
