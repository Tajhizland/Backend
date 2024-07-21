<?php

namespace App\Repositories\New;

use App\Models\News;
use App\Repositories\Base\BaseRepository;

class NewRepository extends BaseRepository implements NewRepositoryInterface
{
    public function __construct(News $model)
    {
        parent::__construct($model);
    }
}
