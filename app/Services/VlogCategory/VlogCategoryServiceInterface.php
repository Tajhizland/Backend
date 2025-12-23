<?php

namespace App\Services\VlogCategory;

interface VlogCategoryServiceInterface
{
    public function dataTable();

    public function getActiveList();

    public function findById($id);

    public function store($name, $status, $url);

    public function update($id, $name, $status, $url);
}
