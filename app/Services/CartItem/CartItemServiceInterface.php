<?php

namespace App\Services\CartItem;

interface CartItemServiceInterface
{
    public function calculatePrice($cartItems): array;

    public function checkAllow($cartItems): bool;
    public function checkLimit($cartItems): bool;
    public function convertCartItemToOrderItem($cartItems , $orderId): bool;
}
