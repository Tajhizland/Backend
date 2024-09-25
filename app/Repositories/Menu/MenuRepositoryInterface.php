<?php

namespace App\Repositories\Menu;

use App\Models\Menu;
use App\Repositories\Base\BaseRepositoryInterface;

interface MenuRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function getWithChildren();
    public function store($title, $parentId, $url, $bannerTitle, $bannerUrl, $logoPath);
    public function updateMenu(Menu $menu ,$title, $parentId, $url, $bannerTitle, $bannerUrl, $logoPath);
}
