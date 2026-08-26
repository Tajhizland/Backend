<?php

namespace App\Services\Guaranty;

use App\DTOs\Guaranty\GuarantyStoreDto;
use App\DTOs\Guaranty\GuarantyUpdateDto;

interface GuarantyServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function findByUrl($url): mixed;

    public function getActives(): mixed;

    public function store(GuarantyStoreDto $dto): mixed;

    public function update(GuarantyUpdateDto $dto): bool;

    public function calculatePrice(float $price): float;

    public function getSitemapData(): mixed;
}
