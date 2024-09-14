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

    public function setOption($categoryId , $options): void;


}
