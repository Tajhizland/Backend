<?php

namespace App\Services\Compare;

interface CompareServiceInterface
{
    public function findProductCompare($productId);

    public function searchProductCompare($query , $categoryIds);

}
