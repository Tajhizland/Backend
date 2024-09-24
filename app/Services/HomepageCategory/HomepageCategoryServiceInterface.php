<?php

namespace App\Services\HomepageCategory;

interface HomepageCategoryServiceInterface
{
    public function dataTable();
    public function add($categoryId);
    public function delete($id);
}
