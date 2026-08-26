<?php

namespace App\Services\Filter;

use App\DTOs\Filter\FilterSetDto;
use App\DTOs\Product\ProductSetFilterDto;


interface FilterServiceInterface
{
    public function apply($productQuery , $filters);
    public function findById($id);
    public function dataTable();
    public function createFilter($name,$categoryId,$status,$type,$items);
    public function updateFilter($id,$name,$categoryId,$status,$type,$items);
    public function getByProductId($productId);
    public function setFilterToProduct(ProductSetFilterDto $dto): void;
    public function getCategoryFilters($categoryId);
    public function setFilter(FilterSetDto $dto): void;
}
