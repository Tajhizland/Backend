<?php

namespace App\Services\Gateway;

use App\DTOs\Gateway\GatewayStoreDto;
use App\DTOs\Gateway\GatewayUpdateDto;

interface GatewayServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function findActiveGateway(): mixed;

    public function store(GatewayStoreDto $dto): mixed;

    public function update(GatewayUpdateDto $dto): bool;
}
