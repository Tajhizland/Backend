<?php

namespace App\Repositories\CastCategory;

use App\Repositories\Base\BaseRepositoryInterface;

interface CastCategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function getActives();
}
