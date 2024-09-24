<?php

namespace App\Services\PopularCategory;

interface PopularCategoryServiceInterface
{
    public function dataTable();
    public function add($categoryId);
    public function delete($id);
}
