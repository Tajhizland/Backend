<?php

namespace App\Repositories\TrustedBrand;

use App\Models\TrustedBrand;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TrustedBrandRepository extends BaseRepository implements TrustedBrandRepositoryInterface
{
    public function __construct(TrustedBrand $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(TrustedBrand::class)
            ->allowedFilters(['id'])
            ->allowedSorts(['id'])
            ->paginate($this->pageSize);
    }
}
