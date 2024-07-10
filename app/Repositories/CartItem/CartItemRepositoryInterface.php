<?php

namespace App\Repositories\CartItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface CartItemRepositoryInterface extends  BaseRepositoryInterface
{
    public function getItemsByCartId($cartId);
    public function findItem($cartId, $productId);
    public function addItem($cartId, $productId, $quantity);
    public function updateItem($cartId, $productId, $quantity);
    public function removeItem($cartId, $productId);
    public function clearItems($cartId);
}
