<?php

namespace App\Repositories\Category;

use App\Repositories\Base\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends  BaseRepositoryInterface
{
    public function search($query);
    public function findByUrl($url);
}
