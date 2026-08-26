<?php

namespace App\Services\Banner;

use App\DTOs\Banner\BannerSortDto;
use App\DTOs\Banner\BannerStoreDto;
use App\DTOs\Banner\BannerUpdateDto;

interface BannerServiceInterface
{
    public function dataTable(): mixed;

    public function getAll(): mixed;

    public function find(int $id): mixed;

    public function store(BannerStoreDto $dto): mixed;

    public function update(BannerUpdateDto $dto): bool;

    public function sort(BannerSortDto $dto): bool;

    public function delete(int $id): bool|null;

    public function getBlogBanner(): mixed;

    public function getVlogBanner(): mixed;

    public function getBrandBanner(): mixed;

    public function getSpecialBanner(): mixed;

    public function getDiscountedBanner(): mixed;
}
