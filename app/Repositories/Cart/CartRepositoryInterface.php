<?php

namespace App\Repositories\Cart;

use App\Repositories\Base\BaseRepositoryInterface;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    public function getCartByUserId($userId);
    public function createCart($userId);
}
