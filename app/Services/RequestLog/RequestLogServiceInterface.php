<?php

namespace App\Services\RequestLog;

use App\DTOs\RequestLog\RequestLogStoreDto;

interface RequestLogServiceInterface
{
    public function store(RequestLogStoreDto $dto): mixed;

    public function log(?string $title, mixed $request = null, mixed $response = null): void;

    public function dataTable();
}
