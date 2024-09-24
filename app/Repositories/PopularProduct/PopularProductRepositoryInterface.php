<?php

namespace App\Repositories\PopularProduct;

use App\Repositories\Base\BaseRepositoryInterface;

interface PopularProductRepositoryInterface extends  BaseRepositoryInterface
{
    public function add($productId);
    public function dataTable();
}
