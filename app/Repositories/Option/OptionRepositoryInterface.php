<?php

namespace App\Repositories\Option;

use App\Repositories\Base\BaseRepositoryInterface;

interface OptionRepositoryInterface extends BaseRepositoryInterface
{
    public function createOption($title, $categoryId, $status, $sort = null);

    public function updateOption($id, $title, $categoryId, $status);

    public function dataTable();

    public function find($id);
    public function sort($id , $sort);

    public function getByProductId($productId);

    public function getCategoryOptions($categoryId);

    public function findLastSortOfCategory($categoryId);
}
