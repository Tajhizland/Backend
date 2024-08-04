<?php

namespace App\Services\Category;

interface CategoryServiceInterface
{
    public function listing($url , $filters);
    public function findById($id);
    public function dateTable();
    public function storeCategory($name,$status,$url,$image,$description,$parentId);
    public function updateCategory($id,$name,$status,$url,$image,$description,$parentId);
}
