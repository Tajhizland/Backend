<?php

namespace App\Services\BlogCategory;

use App\DTOs\BlogCategory\BlogCategoryStoreDto;
use App\DTOs\BlogCategory\BlogCategoryUpdateDto;

interface BlogCategoryServiceInterface
{
    public function dataTable(): mixed;

    public function list(): mixed;

    public function find(int $id): mixed;

    public function store(BlogCategoryStoreDto $dto): mixed;

    public function update(BlogCategoryUpdateDto $dto): bool;
}
