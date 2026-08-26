<?php

namespace App\Services\Filter;

use App\DTOs\Filter\FilterSetDto;
use App\DTOs\Filter\FilterStoreDto;
use App\DTOs\Filter\FilterUpdateDto;
use App\DTOs\Product\ProductSetFilterDto;


interface FilterServiceInterface
{
    public function apply($productQuery , $filters);
    public function findById($id);
    public function dataTable();
    public function createFilter(FilterStoreDto $dto): bool;
    public function updateFilter(FilterUpdateDto $dto): bool;
    public function getByProductId($productId);
    public function setFilterToProduct(ProductSetFilterDto $dto): void;
    public function getCategoryFilters($categoryId);
    public function setFilter(FilterSetDto $dto): void;
}
