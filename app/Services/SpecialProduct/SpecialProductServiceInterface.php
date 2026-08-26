<?php

namespace App\Services\SpecialProduct;

use App\DTOs\SpecialProduct\SpecialProductAddDto;
use App\DTOs\SpecialProduct\SpecialProductHomepageDto;
use App\DTOs\SpecialProduct\SpecialProductSortDto;

interface SpecialProductServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function add(SpecialProductAddDto $dto): mixed;

    public function delete(int $id): bool|null;

    public function showHomepage(SpecialProductHomepageDto $dto): bool;

    public function sort(SpecialProductSortDto $dto): bool;
}
