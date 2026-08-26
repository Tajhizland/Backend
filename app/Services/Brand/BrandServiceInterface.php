<?php

namespace App\Services\Brand;

use App\DTOs\Brand\BrandSortDto;
use App\DTOs\Brand\BrandStoreDto;
use App\DTOs\Brand\BrandUpdateDto;

interface BrandServiceInterface
{
    public function dataTable(): mixed;

    public function list(): mixed;

    public function find(int $id): mixed;

    public function listing($url, $filters): mixed;

    public function store(BrandStoreDto $dto): mixed;

    public function update(BrandUpdateDto $dto): bool;

    public function sort(BrandSortDto $dto): bool;

    public function getAllActive(): mixed;

    public function getSitemapData(): mixed;
}
