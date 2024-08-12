<?php

namespace App\Services\Cart;

use App\Models\Cart;

interface CartServiceInterface
{
    public function getCartItems($userId);
    public function addProductToCart($userId, $productColorId, $quantity);
    public function removeProductFromCart($userId, $productColorId);
    public function clearCart($userId);
    public function decreaseProductInCart($userId, $productColorId);
    public function increaseProductInCart($userId, $productColorId);
    public function setDeliveryMethod( $userId, $delivery_method);
    public function setPaymentMethod( $userId, $payment_method);
}
