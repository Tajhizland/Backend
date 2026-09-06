<?php

namespace App\Repositories\Filter;

use App\Repositories\Base\BaseRepositoryInterface;

interface FilterRepositoryInterface extends BaseRepositoryInterface
{
    public function createFilter($name, $categoryId, $status);
    public function findLastSortOfCategory($categoryId);
    public function sort($id, $sort);
    public function updateFilter($id,$name, $categoryId, $status);
    public function dataTable();
    public function getByProductId($productId);
    public function getCategoryFilters($categoryId);
}
