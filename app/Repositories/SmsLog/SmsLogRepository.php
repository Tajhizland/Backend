<?php

namespace App\Repositories\SmsLog;

use App\Models\SmsLog;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class SmsLogRepository extends BaseRepository implements SmsLogRepositoryInterface
{
    public function __construct(SmsLog $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(SmsLog::class)
        ->allowedFilters(['id', 'type', 'created_at'])
        ->allowedSorts(['id', 'type', 'created_at'])
        ->paginate($this->pageSize);
    }
}
