<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    public function findProductByUrl(string $url):mixed;
}
