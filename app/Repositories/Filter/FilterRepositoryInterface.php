<?php

namespace App\Repositories\Filter;

use App\Repositories\Base\BaseRepositoryInterface;

interface FilterRepositoryInterface extends BaseRepositoryInterface
{
    public function createFilter($name, $categoryId, $status, $type);
    public function updateFilter($id,$name, $categoryId, $status, $type);
    public function dataTable();
}