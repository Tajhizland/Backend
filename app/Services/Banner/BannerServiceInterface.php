<?php

namespace App\Services\Banner;

interface BannerServiceInterface
{
    public function dataTable();
    public function delete($id);
    public function findById($id);
    public function create($image,$url);
    public function update($id,$image,$url);
}