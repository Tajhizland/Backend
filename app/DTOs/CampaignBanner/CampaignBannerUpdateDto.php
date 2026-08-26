<?php

namespace App\DTOs\CampaignBanner;

class CampaignBannerUpdateDto
{
    public function __construct(
        public int    $campaignBannerId,
        public string $url,
        public mixed  $type,
        public mixed  $image = null,
    )
    {
    }
}
