<?php

namespace App\Repositories\Sample;

use App\Models\Sample;
use App\Repositories\Base\BaseRepository;

class SampleRepository extends BaseRepository implements SampleRepositoryInterface
{
    public function __construct(Sample $model)
    {
        parent::__construct($model);
    }

    public function first()
    {
        return $this->model::first();
    }
}
