<?php

namespace App\Services\Brand;

interface BrandServiceInterface
{
    public function dataTable();
    public function findById($id);
    public function storeBrand($name,$url ,$status,$image,$description);
    public function updateBrand($id , $name,$url ,$status,$image,$description);
}
