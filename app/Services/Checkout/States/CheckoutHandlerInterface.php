<?php

namespace App\Services\Checkout\States;

use App\Models\Cart;
use App\Models\CartItem;

interface CheckoutHandlerInterface
{
    public function setNext(CheckoutHandlerInterface $handler): void;
    public function handle(Cart $cart, CartItem $cartItem);
}
