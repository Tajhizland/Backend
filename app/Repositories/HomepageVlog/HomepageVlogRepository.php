<?php

namespace App\Repositories\HomepageVlog;

use App\Models\HomepageVlog;
use App\Repositories\Base\BaseRepository;

class HomepageVlogRepository extends BaseRepository implements HomepageVlogRepositoryInterface
{
    public function __construct(HomepageVlog $model)
    {
        parent::__construct($model);
    }

    public function getWithVlog()
    {
        return $this->model->with(['vlog'])->get();
    }
}
