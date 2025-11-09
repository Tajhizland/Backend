<?php

namespace App\Repositories\Cast;

use App\Repositories\Base\BaseRepositoryInterface;

interface CastRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();

    public function findWithVlog($id);
}
