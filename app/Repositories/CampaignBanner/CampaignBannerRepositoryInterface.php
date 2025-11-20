<?php

namespace App\Repositories\CampaignBanner;

use App\Repositories\Base\BaseRepositoryInterface;

interface CampaignBannerRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable($campaign_id);

    public function sort($id, $sort);

    public function getBannerByType($type);

}
