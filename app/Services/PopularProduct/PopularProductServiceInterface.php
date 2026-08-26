<?php

namespace App\Services\PopularProduct;

use App\DTOs\PopularProduct\PopularProductAddDto;

interface PopularProductServiceInterface
{
    public function dataTable(): mixed;

    public function get(): mixed;

    public function find(int $id): mixed;

    public function add(PopularProductAddDto $dto): mixed;

    public function delete(int $id): bool|null;
}
