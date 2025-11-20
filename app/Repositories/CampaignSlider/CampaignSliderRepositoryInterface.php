<?php

namespace App\Repositories\CampaignSlider;

use App\Repositories\Base\BaseRepositoryInterface;

interface CampaignSliderRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllDesktop();
    public function getAllMobile();
    public function sort($id,$sort);
    public function getByCampaignId($campaignId);

}
