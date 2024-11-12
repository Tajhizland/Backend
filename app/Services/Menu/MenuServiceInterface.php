<?php

namespace App\Services\Menu;

interface MenuServiceInterface
{
    public function dataTable();

    public function list();

    public function findById($id);

    public function store($title, $parentId, $url, $bannerTitle, $bannerUrl, $bannerLogo);

    public function update($id, $title, $parentId, $url, $bannerTitle, $bannerUrl, $bannerLogo);

    public function buildMenu();
}
