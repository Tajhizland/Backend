<?php

namespace App\Services\CampaignBanner;

interface CampaignBannerServiceInterface
{
    public function dataTable($campaign_id);

    public function getByType($type);

    public function sort($array);

    public function delete($id);

    public function findById($id);

    public function create($image, $url, $type, $campaign_id);

    public function update($id, $image, $url, $type);
}
