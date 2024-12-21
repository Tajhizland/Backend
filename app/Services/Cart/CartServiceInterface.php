<?php

namespace App\Services\Cart;

use App\Models\Cart;

interface CartServiceInterface
{
    public function getCartItems($userId);
    public function addProductToCart($userId, $productColorId, $quantity,$guarantyId);
    public function removeProductFromCart($userId, $productColorId,$guarantyId);
    public function clearCart($userId);
    public function decreaseProductInCart($userId, $productColorId,$guarantyId);
    public function increaseProductInCart($userId, $productColorId,$guarantyId);
    public function setDeliveryMethod( $userId, $delivery_method);
    public function setPaymentMethod( $userId, $payment_method);
}
