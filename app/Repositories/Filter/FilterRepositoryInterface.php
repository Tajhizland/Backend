<?php

namespace App\Repositories\Filter;

use App\Repositories\Base\BaseRepositoryInterface;

interface FilterRepositoryInterface extends BaseRepositoryInterface
{
    public function createFilter($name, $categoryId, $status);
    public function updateFilter($id,$name, $categoryId, $status);
    public function dataTable();
    public function find($id);
    public function getByProductId($productId);
    public function getCategoryFilters($categoryId);
}
