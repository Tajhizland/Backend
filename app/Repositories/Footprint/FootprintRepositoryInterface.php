<?php

namespace App\Repositories\Footprint;

use App\Repositories\Base\BaseRepositoryInterface;
use Carbon\Carbon;

interface FootprintRepositoryInterface extends BaseRepositoryInterface
{
    public function allChartData($fromDate,$toDate);
    public function ipChartData($fromDate,$toDate);

    public function deviceBreakdown(Carbon $from, Carbon $to);

    public function deviceDailyCounts(Carbon $from, Carbon $to);

    public function attributeBreakdown(string $column, Carbon $from, Carbon $to, int $limit);
}
