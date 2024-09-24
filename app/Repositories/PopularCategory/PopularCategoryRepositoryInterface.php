<?php

namespace App\Repositories\PopularCategory;

use App\Repositories\Base\BaseRepositoryInterface;

interface PopularCategoryRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function add($categoryId);
}
