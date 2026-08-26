<?php

namespace App\Services\Cart;

use App\DTOs\Cart\CartAddItemDto;
use App\DTOs\Cart\CartItemDto;
use App\DTOs\Cart\CartMergeDto;

use App\Models\Cart;

interface CartServiceInterface
{
    public function getCartItems($userId);
    public function mergeCart(CartMergeDto $dto): mixed;
    public function addProductToCart(CartAddItemDto $dto): mixed;
    public function removeProductFromCart(CartItemDto $dto): mixed;
    public function clearCart($userId);
    public function decreaseProductInCart(CartItemDto $dto): mixed;
    public function increaseProductInCart(CartItemDto $dto): mixed;
    public function setDeliveryMethod( $userId, $delivery_method);
    public function setPaymentMethod( $userId, $payment_method);
}
