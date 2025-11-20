<?php

namespace App\Services\CampaignSlider;

interface CampaignSliderServiceInterface
{
    public function store($title, $url, $status, $type, $image, $campaignId);

    public function find($id);
    public function delete($id);

    public function update($id, $title, $url, $status, $type, $image);

    public function getAllDesktop();

    public function getAllMobile();

    public function sort($sliders);

    public function getByCampaignId($campaignId);

}
