<?php

namespace App\Repositories\Footprint;

use App\Repositories\Base\BaseRepositoryInterface;

interface FootprintRepositoryInterface extends BaseRepositoryInterface
{
    public function allChartData();
    public function ipChartData();
}
