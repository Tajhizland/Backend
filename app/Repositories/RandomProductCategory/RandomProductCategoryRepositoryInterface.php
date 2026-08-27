<?php

namespace App\Repositories\RandomProductCategory;

use App\Repositories\Base\BaseRepositoryInterface;

interface RandomProductCategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();

    public function add($categoryId);

    public function getRandomProductCards(?int $limit = null);
}
