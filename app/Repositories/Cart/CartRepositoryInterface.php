<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Repositories\Base\BaseRepositoryInterface;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    public function getCartByUserId($userId);
    public function createCart($userId);
    public function changeStatus(Cart $cart,$status);
}
