<?php

namespace App\Services\New;

use App\DTOs\News\NewsStoreDto;
use App\DTOs\News\NewsUpdateDto;

interface NewServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function findByUrl($url): mixed;

    public function activePaginate($filters): mixed;

    public function store(NewsStoreDto $dto): mixed;

    public function update(NewsUpdateDto $dto): bool;

    public function getSitemapData(): mixed;

    public function getLastPost(): mixed;
}
