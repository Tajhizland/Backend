<?php

namespace App\Repositories\PhoneBock;

use App\Models\PhoneBock;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class PhoneBockRepository extends BaseRepository implements PhoneBockRepositoryInterface
{
    public function __construct(PhoneBock $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(PhoneBock::class)
            ->allowedFilters(['name', 'mobile', 'id', 'created_at'])
            ->allowedSorts(['name', 'mobile', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }
}
