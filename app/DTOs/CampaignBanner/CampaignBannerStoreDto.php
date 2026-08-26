<?php

namespace App\DTOs\CampaignBanner;

class CampaignBannerStoreDto
{
    public function __construct(
        public int    $campaign_id,
        public mixed  $image,
        public string $url,
        public mixed  $type,
    )
    {
    }
}
