<?php

namespace App\Services\Option;

interface OptionServiceInterface
{
    public function findById($id);

    public function dataTable();

    public function createOption($title, $categoryId, $status, $items);

    public function updateOption($id, $title, $categoryId, $status, $items);

    public function getByProductId($productId);

    public function setOptionToProduct($productId, $options): void;

    public function getCategoryOptions($categoryId);

    public function setOption($categoryId, $options): void;

    public function sortOption($options);

    public function sortOptionItem($options);

    public function getItemOfOption($optionId);


    public function updateOptionItem($id, $categoryId,$title, $status);


}
