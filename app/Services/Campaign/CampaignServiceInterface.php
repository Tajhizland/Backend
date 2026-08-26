<?php

namespace App\Services\Campaign;

use App\DTOs\Campaign\CampaignStoreDto;
use App\DTOs\Campaign\CampaignUpdateDto;

interface CampaignServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function store(CampaignStoreDto $dto): mixed;

    public function update(CampaignUpdateDto $dto): bool;

    public function findActiveCampaign(): mixed;

    public function findPendingActiveCampaign(): mixed;
}
