<?php

namespace App\Repositories\CategoryViewHistory;

use App\Models\CategoryViewHistory;
use App\Repositories\Base\BaseRepository;

class CategoryViewHistoryRepository extends BaseRepository implements CategoryViewHistoryRepositoryInterface
{
    public function __construct(CategoryViewHistory $model)
    {
        parent::__construct($model);
    }
}
