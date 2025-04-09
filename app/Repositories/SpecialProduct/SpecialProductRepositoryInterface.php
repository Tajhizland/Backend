<?php

namespace App\Repositories\SpecialProduct;

use App\Repositories\Base\BaseRepositoryInterface;

interface SpecialProductRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function add($productId);
    public function getWithProduct();
    public function sort($id,$sort);


}
