<?php

namespace App\Services\Cast;

use App\DTOs\Cast\CastStoreDto;
use App\DTOs\Cast\CastUpdateDto;

interface CastServiceInterface
{
    public function paginated(): mixed;

    public function listing($filters): mixed;

    public function dataTable(): mixed;

    public function getMostViewed(): mixed;

    public function find(int $id): mixed;

    public function findByUrl($url): mixed;

    public function store(CastStoreDto $dto): mixed;

    public function update(CastUpdateDto $dto): bool;
}
