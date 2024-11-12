<?php

namespace App\Repositories\Banner;

use App\Models\Banner;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Banner::class)
            ->allowedFilters(['url', 'id', 'created_at', 'updated_at'])
            ->allowedSorts(['url', 'id', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }
}
