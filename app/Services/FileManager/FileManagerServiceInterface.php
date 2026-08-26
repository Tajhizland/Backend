<?php

namespace App\Services\FileManager;

use App\DTOs\FileManager\FileManagerGetDto;
use App\DTOs\FileManager\FileManagerUploadDto;

interface FileManagerServiceInterface
{
    public function getByModel(FileManagerGetDto $dto): mixed;

    public function upload(FileManagerUploadDto $dto): mixed;

    public function remove(int $id): bool|null;
}
