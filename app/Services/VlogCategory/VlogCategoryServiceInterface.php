<?php

namespace App\Services\VlogCategory;

use App\DTOs\VlogCategory\VlogCategorySortDto;
use App\DTOs\VlogCategory\VlogCategoryStoreDto;
use App\DTOs\VlogCategory\VlogCategoryUpdateDto;

interface VlogCategoryServiceInterface
{
    public function dataTable(): mixed;

    public function getActiveList(): mixed;

    public function find(int $id): mixed;

    public function store(VlogCategoryStoreDto $dto): mixed;

    public function update(VlogCategoryUpdateDto $dto): bool;

    public function sort(VlogCategorySortDto $dto): bool;
}
