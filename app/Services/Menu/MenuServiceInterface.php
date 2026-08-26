<?php

namespace App\Services\Menu;

use App\DTOs\Menu\MenuStoreDto;
use App\DTOs\Menu\MenuUpdateDto;

interface MenuServiceInterface
{
    public function dataTable(): mixed;

    public function list(): mixed;

    public function find(int $id): mixed;

    public function store(MenuStoreDto $dto): mixed;

    public function update(MenuUpdateDto $dto): bool;

    public function delete(int $id): bool|null;

    public function deleteBanner(int $id): bool;

    public function buildMenu(): mixed;
}
