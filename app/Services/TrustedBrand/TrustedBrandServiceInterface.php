<?php

namespace App\Services\TrustedBrand;

use App\DTOs\TrustedBrand\TrustedBrandStoreDto;
use App\DTOs\TrustedBrand\TrustedBrandUpdateDto;

interface TrustedBrandServiceInterface
{
    public function get(): mixed;

    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function store(TrustedBrandStoreDto $dto): mixed;

    public function update(TrustedBrandUpdateDto $dto): bool;

    public function delete(int $id): bool|null;
}
