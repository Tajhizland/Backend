<?php

namespace App\Services\Banner;

interface BannerServiceInterface
{
    public function dataTable();
    public function getAll();
    public function sort($array);
    public function delete($id);
    public function findById($id);
    public function create($image,$url,$type);
    public function update($id,$image,$url,$type);

    public function getBlogBanner();
    public function getVlogBanner();
    public function getBrandBanner();
    public function getSpecialBanner();
    public function getDiscountedBanner();

}
