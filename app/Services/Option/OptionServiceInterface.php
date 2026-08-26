<?php

namespace App\Services\Option;

use App\DTOs\Option\OptionStoreDto;
use App\DTOs\Option\OptionUpdateDto;

interface OptionServiceInterface
{
    public function find(int $id): mixed;
    public function getByProductIdAndCategoryId($productId, $categoryId);

    public function dataTable();

    public function store(OptionStoreDto $dto): bool;

    public function update(OptionUpdateDto $dto): bool;

    public function getByProductId($productId);

    public function setOptionToProduct($productId, $options): void;

    public function getCategoryOptions($categoryId);

    public function setOption($categoryId, $options): void;

    public function sortOption($options);

    public function sortOptionItem($options);

    public function getItemOfOption($optionId);


    public function updateOptionItem($id, $categoryId,$title, $status);
    public function updateProductOption($id, $productId, $value, $optionItemId);


}
