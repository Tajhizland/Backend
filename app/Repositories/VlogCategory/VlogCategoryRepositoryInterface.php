<?php

namespace App\Repositories\VlogCategory;

use App\Repositories\Base\BaseRepositoryInterface;

interface VlogCategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function getActiveList();
}
