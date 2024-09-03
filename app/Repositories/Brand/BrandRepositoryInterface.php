<?php

namespace App\Repositories\Brand;

use App\Models\Brand;
use App\Repositories\Base\BaseRepositoryInterface;

interface BrandRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function list();

    public function storeBrand($name, $url, $status, $image, $description);

    public function updateBrand(Brand $brand, $name, $url, $status, $image, $description);
}
