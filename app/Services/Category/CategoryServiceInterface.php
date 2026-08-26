<?php

namespace App\Services\Category;

use App\DTOs\Category\CategoryProductSortDto;
use App\DTOs\Category\CategoryStoreDto;
use App\DTOs\Category\CategoryUpdateDto;

interface CategoryServiceInterface
{
    public function listing($url , $filters);
    public function groupListing($url);

    public function find(int $id): mixed;
    public function dataTable();
    public function getStockProductCategory();
    public function list();
    public function productList($id);
    public function productSort(CategoryProductSortDto $dto): bool;
    public function searchCategory($query);
    public function store(CategoryStoreDto $dto): mixed;
    public function update(CategoryUpdateDto $dto): bool;
    public function deleteImage($categoryId);
    public function getSitemapData();
    public function getDiscountedCategory();

}
