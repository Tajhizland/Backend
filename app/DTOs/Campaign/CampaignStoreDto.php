<?php

namespace App\DTOs\Campaign;

class CampaignStoreDto
{
    public function __construct(
        public string $title,
        public string $color,
        public string $background_color,
        public int    $status,
        public mixed  $logo,
        public mixed  $discount_logo,
        public mixed  $start_date = null,
        public mixed  $end_date = null,
        public mixed  $banner = null,
    )
    {
    }
}
