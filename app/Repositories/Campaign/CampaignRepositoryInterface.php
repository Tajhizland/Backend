<?php

namespace App\Repositories\Campaign;

use App\Repositories\Base\BaseRepositoryInterface;

interface CampaignRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();

    public function findActiveCampaign();


}
