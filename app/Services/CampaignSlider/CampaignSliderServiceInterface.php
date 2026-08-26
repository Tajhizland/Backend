<?php

namespace App\Services\CampaignSlider;

use App\DTOs\CampaignSlider\CampaignSliderSortDto;
use App\DTOs\CampaignSlider\CampaignSliderStoreDto;
use App\DTOs\CampaignSlider\CampaignSliderUpdateDto;

interface CampaignSliderServiceInterface
{
    public function getByCampaignId($campaignId): mixed;

    public function getAllDesktop(): mixed;

    public function getAllMobile(): mixed;

    public function find(int $id): mixed;

    public function store(CampaignSliderStoreDto $dto): mixed;

    public function update(CampaignSliderUpdateDto $dto): bool;

    public function delete(int $id): bool|null;

    public function sort(CampaignSliderSortDto $dto): bool;
}
