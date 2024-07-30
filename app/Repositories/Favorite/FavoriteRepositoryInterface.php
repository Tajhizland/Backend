<?php

namespace App\Repositories\Favorite;

use App\Repositories\Base\BaseRepositoryInterface;

interface FavoriteRepositoryInterface extends  BaseRepositoryInterface
{
    public function addProduct($productId , $userId);
    public function removeProduct($productId , $userId);
    public function findProduct($productId,$userId);

}
