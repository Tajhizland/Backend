<?php

namespace App\Services\Delivery;

use App\DTOs\Delivery\DeliveryStoreDto;
use App\DTOs\Delivery\DeliveryUpdateDto;

interface DeliveryServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function getActives(): mixed;

    public function store(DeliveryStoreDto $dto): mixed;

    public function update(DeliveryUpdateDto $dto): bool;
}
