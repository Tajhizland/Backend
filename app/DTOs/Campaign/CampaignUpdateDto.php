<?php

namespace App\DTOs\Campaign;

class CampaignUpdateDto
{
    public function __construct(
        public int    $campaignId,
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
