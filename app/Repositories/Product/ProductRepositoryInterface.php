<?php

namespace App\Repositories\Product;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUrl($url);
    public function incrementViewCount($product);
}
