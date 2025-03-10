<?php

namespace App\Repositories\Brand;

use App\Models\Brand;
use App\Repositories\Base\BaseRepositoryInterface;

interface BrandRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function list();
    public function findByUrl($url);
    public function sort($id,$sort);
    public function getAllActive();
    public function storeBrand($name, $url, $status, $image, $description);
    public function updateBrand(Brand $brand, $name, $url, $status, $image, $description);
    public function getSitemapData();

}
