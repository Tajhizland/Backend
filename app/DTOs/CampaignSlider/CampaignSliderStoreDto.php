<?php

namespace App\DTOs\CampaignSlider;

class CampaignSliderStoreDto
{
    public function __construct(
        public int    $campaign_id,
        public string $title,
        public string $url,
        public int    $status,
        public mixed  $type,
        public mixed  $image,
        public mixed  $sort = null,
    )
    {
    }
}
