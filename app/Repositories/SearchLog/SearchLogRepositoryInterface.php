<?php

namespace App\Repositories\SearchLog;

use App\Repositories\Base\BaseRepositoryInterface;
use Carbon\Carbon;

interface SearchLogRepositoryInterface extends BaseRepositoryInterface
{
    public function record(array $data): mixed;

    public function topTerms(Carbon $from, Carbon $to, int $limit);

    public function zeroResultTerms(Carbon $from, Carbon $to, int $limit);

    public function dailyCounts(Carbon $from, Carbon $to);

    public function totals(Carbon $from, Carbon $to): array;

    public function recentForVisitor(?string $sessionId, ?string $ip, Carbon $since, int $limit = 10);

    public function dataTable();
}
