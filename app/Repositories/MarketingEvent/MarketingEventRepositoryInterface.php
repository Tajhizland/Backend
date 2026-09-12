<?php

namespace App\Repositories\MarketingEvent;

use App\Repositories\Base\BaseRepositoryInterface;
use Carbon\Carbon;

interface MarketingEventRepositoryInterface extends BaseRepositoryInterface
{
    public function record(array $data): mixed;

    public function topProducts(string $type, Carbon $from, Carbon $to, int $limit);

    public function topCategories(string $type, Carbon $from, Carbon $to, int $limit);

    public function dailyCounts(string $type, Carbon $from, Carbon $to);

    public function countEvents(string $type, Carbon $from, Carbon $to): int;

    public function countVisitors(string $type, Carbon $from, Carbon $to): int;

    public function engagementMatrix(Carbon $from, Carbon $to, int $limit, int $minViews = 1, string $orderBy = 'views');

    public function unmetDemand(Carbon $from, Carbon $to, int $limit);

    public function deviceConversion(Carbon $from, Carbon $to);
}
