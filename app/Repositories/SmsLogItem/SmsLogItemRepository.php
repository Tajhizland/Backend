<?php

namespace App\Repositories\SmsLogItem;

use App\Models\SmsLogItem;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class SmsLogItemRepository extends BaseRepository implements SmsLogItemRepositoryInterface
{
    public function __construct(SmsLogItem $model)
    {
        parent::__construct($model);
    }

    public function dataTable($id)
    {
        return QueryBuilder::for(SmsLogItem::class)
            ->where("sms_log_id", $id)
            ->allowedFilters(...['message', 'mobile', 'created_at', 'id', 'is_send'])
            ->allowedSorts(...['message', 'mobile', 'created_at', 'id', 'is_send'])
            ->paginate($this->pageSize);
    }
}
