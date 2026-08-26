<?php

namespace App\DTOs\CampaignSlider;

class CampaignSliderUpdateDto
{
    public function __construct(
        public int    $campaignSliderId,
        public string $title,
        public string $url,
        public int    $status,
        public mixed  $type,
        public mixed  $image = null,
        public mixed  $sort = null,
    )
    {
    }
}
