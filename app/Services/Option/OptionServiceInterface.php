<?php

namespace App\Services\Option;

use App\DTOs\Option\OptionItemSortDto;
use App\DTOs\Product\ProductSetOptionDto;
use App\DTOs\Option\OptionItemUpdateDto;
use App\DTOs\Option\OptionSetDto;
use App\DTOs\Option\OptionSortDto;
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

    public function setOptionToProduct(ProductSetOptionDto $dto): void;

    public function getCategoryOptions($categoryId);

    public function setOption(OptionSetDto $dto): void;

    public function sortOption(OptionSortDto $dto): mixed;

    public function sortOptionItem(OptionItemSortDto $dto): mixed;

    public function getItemOfOption($optionId);


    public function updateOptionItem(OptionItemUpdateDto $dto): mixed;
    public function updateProductOption($id, $productId, $value, $optionItemId);


}
