<?php

namespace App\Services\Filter;


interface FilterServiceInterface
{
    public function apply($productQuery , $filters);
    public function findById($id);
    public function dataTable();
    public function createFilter($name,$categoryId,$status,$type,$items);
    public function updateFilter($id,$name,$categoryId,$status,$type,$items);
    public function getByProductId($productId);
    public function setFilterToProduct($productId , $filters):void;
    public function getCategoryFilters($categoryId);
    public function setFilter($categoryId , $filters):void;
}
