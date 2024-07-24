<?php

namespace App\Services\Cart;

interface CartServiceInterface
{
    public function getCartItems($userId);
    public function addProductToCart($userId, $productColorId, $quantity);
    public function removeProductFromCart($userId, $productColorId);
    public function clearCart($userId);
    public function decreaseProductInCart($userId, $productColorId);
    public function increaseProductInCart($userId, $productColorId);
}
