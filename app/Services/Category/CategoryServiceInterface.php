<?php

namespace App\Services\Category;

interface CategoryServiceInterface
{
    public function listing($url , $filters);
    public function findById($id);
    public function dataTable();
    public function list();
    public function productList($id);
    public function productSort($array);
    public function searchCategory($query);
    public function storeCategory($name,$status,$url,$image,$description,$parentId);
    public function updateCategory($id,$name,$status,$url,$image,$description,$parentId);
    public function deleteImage($categoryId);

}
