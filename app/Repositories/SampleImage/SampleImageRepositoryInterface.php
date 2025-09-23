<?php

namespace App\Repositories\SampleImage;

use App\Repositories\Base\BaseRepositoryInterface;

interface SampleImageRepositoryInterface extends BaseRepositoryInterface
{
    public function sort($id, $sort);
    public function getAll();
}
