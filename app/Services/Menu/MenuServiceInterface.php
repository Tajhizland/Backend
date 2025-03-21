<?php

namespace App\Services\Menu;

interface MenuServiceInterface
{
    public function dataTable();

    public function delete($id);

    public function list();

    public function findById($id);

    public function store($title, $parentId, $url,  $status, $categoryId, $bannerUrl, $bannerLogo);

    public function update($id, $title, $parentId, $url,  $status, $categoryId, $bannerUrl, $bannerLogo);

    public function buildMenu();

    public function deleteBanner($id);
}
