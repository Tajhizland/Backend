<?php

namespace App\Services\Discount;

use App\DTOs\Discount\DiscountSetItemDto;
use App\DTOs\Discount\DiscountSortDto;
use App\DTOs\Discount\DiscountStoreDto;
use App\DTOs\Discount\DiscountUpdateDto;
use App\DTOs\Discount\DiscountUpdateItemDto;

interface DiscountServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function store(DiscountStoreDto $dto): mixed;

    public function update(DiscountUpdateDto $dto): bool;

    public function getItem($id): mixed;

    public function getTopItem($id): mixed;

    public function deleteItem($id): bool|null;

    public function setItem(DiscountSetItemDto $dto): void;

    public function updateItem(DiscountUpdateItemDto $dto): void;

    public function sort(DiscountSortDto $dto): bool;
}
