<?php

namespace App\Services\PopularProduct;

interface PopularProductServiceInterface
{
    public function add($productId);
    public function delete($id);
    public function dataTable();
}
