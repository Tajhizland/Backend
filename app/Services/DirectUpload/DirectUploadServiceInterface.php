<?php

namespace App\Services\DirectUpload;

use App\DTOs\Upload\UploadAbortDto;
use App\DTOs\Upload\UploadCompleteDto;
use App\DTOs\Upload\UploadInitiateDto;
use App\DTOs\Upload\UploadSignPartsDto;

interface DirectUploadServiceInterface
{
    public function initiate(UploadInitiateDto $dto): array;

    public function signParts(UploadSignPartsDto $dto): array;

    public function complete(UploadCompleteDto $dto): array;

    public function abort(UploadAbortDto $dto): void;

    public function consume(string $key, $userId): string;

    public function prune(int $hours): int;
}
