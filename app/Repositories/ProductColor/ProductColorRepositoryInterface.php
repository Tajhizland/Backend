<?php

namespace App\Repositories\ProductColor;

use App\Repositories\Base\BaseRepositoryInterface;

interface ProductColorRepositoryInterface extends BaseRepositoryInterface
{
    public function createProductColor($name , $code , $productId ,$status);
    public function updateProductColor($id,$name , $code  ,$status);
}
