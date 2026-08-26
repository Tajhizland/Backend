<?php

namespace App\Services\CastCategory;

use App\DTOs\CastCategory\CastCategoryStoreDto;
use App\DTOs\CastCategory\CastCategoryUpdateDto;

interface CastCategoryServiceInterface
{
    public function dataTable(): mixed;

    public function get(): mixed;

    public function find(int $id): mixed;

    public function store(CastCategoryStoreDto $dto): mixed;

    public function update(CastCategoryUpdateDto $dto): bool;
}
