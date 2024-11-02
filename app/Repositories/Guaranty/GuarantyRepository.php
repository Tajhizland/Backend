<?php

namespace App\Repositories\Guaranty;

use App\Models\guaranty;
use App\Repositories\Base\BaseRepository;

class GuarantyRepository extends  BaseRepository implements  GuarantyRepositoryInterface
{
    public function __construct(Guaranty $model)
    {
        parent::__construct($model);
    }
}
