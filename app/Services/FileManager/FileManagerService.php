<?php

namespace App\Services\FileManager;

use App\DTOs\FileManager\FileManagerGetDto;
use App\DTOs\FileManager\FileManagerUploadDto;

use App\Repositories\FileManager\FileManagerRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

readonly class FileManagerService implements FileManagerServiceInterface
{
    public function __construct
    (
        private FileManagerRepositoryInterface $fileManagerRepository,
        private S3ServiceInterface             $s3Service,
    )
    {
    }

    public function upload(FileManagerUploadDto $dto): mixed
    {
        $file = $dto->file;
        $modelType = $dto->model_type;
        $modelId = $dto->model_id;
        $path = [$modelType, "file"];
        $path = join("/", $path);
        $filePath = $this->s3Service->upload($file, $path);
        return $this->fileManagerRepository->create([
            "path" => $filePath,
            "model_type" => $modelType,
            "model_id" => $modelId
        ]);
    }

    public function remove(int $id): bool|null
    {
        $file = $this->fileManagerRepository->findOrFail($id);
        $filePath = [$file->model_type, "file", $file->path];
        $filePath = join("/", $filePath);
        $this->s3Service->remove($filePath);
        $this->fileManagerRepository->delete($file);
        return true;
    }

    public function getByModel(FileManagerGetDto $dto): mixed
    {
        $modelId = $dto->model_id;
        $modelType = $dto->model_type;
        return $this->fileManagerRepository->geyByModelId($modelId, $modelType);
    }

}
