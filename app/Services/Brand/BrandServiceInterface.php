<?php

namespace App\Services\Brand;

interface BrandServiceInterface
{
    public function dataTable();
    public function list();
    public function sort($brands);
    public function getAllActive();
    public function listing($url, $filters);
    public function findById($id);
    public function storeBrand($name,$url ,$status,$image,$description);
    public function updateBrand($id , $name,$url ,$status,$image,$description);
    public function getSitemapData();

}
