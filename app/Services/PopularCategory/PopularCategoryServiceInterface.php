<?php

namespace App\Services\PopularCategory;

use App\DTOs\PopularCategory\PopularCategoryAddDto;

interface PopularCategoryServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function add(PopularCategoryAddDto $dto): mixed;

    public function delete(int $id): bool|null;
}
