<?php

namespace App\Services\Option;

interface OptionServiceInterface
{
    public function findById($id);
    public function dataTable();
    public function createOption($title,$categoryId,$status,$items);
    public function updateOption($id,$title,$categoryId,$status,$items);
}
