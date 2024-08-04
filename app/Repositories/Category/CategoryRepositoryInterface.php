<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Base\BaseRepositoryInterface;

interface CategoryRepositoryInterface extends  BaseRepositoryInterface
{
    public function search($query);
    public function findByUrl($url);
    public function dateTable();
    public function createCategory($name, $status, $url, $image, $description, $parentId);
    public function updateCategory(Category $category,$name, $status, $url, $image, $description, $parentId);
}
