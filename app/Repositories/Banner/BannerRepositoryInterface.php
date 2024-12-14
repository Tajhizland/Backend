<?php

namespace App\Repositories\Banner;

use App\Repositories\Base\BaseRepositoryInterface;

interface BannerRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function getAll();
    public function sort($id,$sort);
}
