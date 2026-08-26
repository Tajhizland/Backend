<?php

namespace App\Services\Page;

use App\DTOs\Page\PageStoreDto;
use App\DTOs\Page\PageUpdateDto;

interface PageServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function findByUrl($url): mixed;

    public function store(PageStoreDto $dto): mixed;

    public function update(PageUpdateDto $dto): bool;
}
