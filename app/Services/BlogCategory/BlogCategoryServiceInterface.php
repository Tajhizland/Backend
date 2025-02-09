<?php

namespace App\Services\BlogCategory;

interface BlogCategoryServiceInterface
{
    public function dataTable();

    public function findById($id);

    public function create($name, $status, $url);

    public function update($id, $name, $status, $url);
}
