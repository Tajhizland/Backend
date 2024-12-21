<?php

namespace App\Repositories\CartItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface CartItemRepositoryInterface extends  BaseRepositoryInterface
{
    public function getItemsByCartId($cartId);
    public function findItem($cartId, $productId,$guarantyId);
    public function addItem($cartId, $productId, $quantity,$guarantyId);
    public function updateItem($cartId, $productId, $quantity,$guarantyId);
    public function removeItem($cartId, $productId,$guarantyId);
    public function clearItems($cartId);
    public function increment($cartItem);
    public function decrement($cartItem);

}
