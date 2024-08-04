<?php

namespace App\Repositories\Option;

use App\Repositories\Base\BaseRepositoryInterface;

interface OptionRepositoryInterface extends  BaseRepositoryInterface
{
    public function createOption($title, $categoryId, $status);
    public function updateOption($id,$title, $categoryId, $status);
    public function dataTable();
}
