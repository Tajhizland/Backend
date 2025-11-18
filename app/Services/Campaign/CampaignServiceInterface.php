<?php

namespace App\Services\Campaign;

interface CampaignServiceInterface
{
    public function dataTable();

    public function find($id);

    public function findActiveCampaign();
    public function findPendingActiveCampaign();

    public function store($title, $status, $color, $startDate, $endDate, $logo, $banner, $background_color, $discount_logo);

    public function update($id, $title, $status, $color, $startDate, $endDate, $logo, $banner, $background_color, $discount_logo);

}
