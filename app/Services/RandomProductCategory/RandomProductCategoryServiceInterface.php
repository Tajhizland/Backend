<?php

namespace App\Services\RandomProductCategory;

use App\DTOs\RandomProductCategory\RandomProductCategoryAddDto;

interface RandomProductCategoryServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function add(RandomProductCategoryAddDto $dto): mixed;

    public function delete(int $id): bool|null;
}
