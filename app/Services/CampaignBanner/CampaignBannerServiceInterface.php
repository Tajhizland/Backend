<?php

namespace App\Services\CampaignBanner;

use App\DTOs\CampaignBanner\CampaignBannerSortDto;
use App\DTOs\CampaignBanner\CampaignBannerStoreDto;
use App\DTOs\CampaignBanner\CampaignBannerUpdateDto;

interface CampaignBannerServiceInterface
{
    public function dataTable($campaign_id): mixed;

    public function find(int $id): mixed;

    public function getByType($type): mixed;

    public function store(CampaignBannerStoreDto $dto): mixed;

    public function update(CampaignBannerUpdateDto $dto): bool;

    public function delete(int $id): bool|null;

    public function sort(CampaignBannerSortDto $dto): bool;
}
