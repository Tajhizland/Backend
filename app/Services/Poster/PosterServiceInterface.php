<?php

namespace App\Services\Poster;

use App\DTOs\Poster\PosterStoreDto;
use App\DTOs\Poster\PosterUpdateDto;

interface PosterServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function store(PosterStoreDto $dto): mixed;

    public function update(PosterUpdateDto $dto): bool;
}
