<?php

namespace App\Services\PhoneBock;

use App\DTOs\PhoneBock\PhoneBockStoreDto;
use App\DTOs\PhoneBock\PhoneBockUpdateDto;

interface PhoneBockServiceInterface
{
    public function dataTable(): mixed;

    public function getAll(): mixed;

    public function find(int $id): mixed;

    public function store(PhoneBockStoreDto $dto): mixed;

    public function update(PhoneBockUpdateDto $dto): bool;
}
