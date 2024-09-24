<?php

namespace App\Repositories\HomepageCategory;

use App\Repositories\Base\BaseRepositoryInterface;

interface HomepageCategoryRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function add($categoryId);
}
