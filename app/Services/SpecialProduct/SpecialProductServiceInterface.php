<?php

namespace App\Services\SpecialProduct;

interface SpecialProductServiceInterface
{
    public function dataTable();
    public function add($productId);
    public function delete($id);
    public function showHomepage($id , $value);
}
