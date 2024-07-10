<?php

namespace App\Services\Cart;

use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;

class CartService implements CartServiceInterface
{
    public function __construct
    (
        private CartRepositoryInterface     $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository
    ){}


    public function getCartItems($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            $cart = $this->cartRepository->createCart($userId);
        }
        return $this->cartItemRepository->getItemsByCartId($cart->id);
    }

    public function addProductToCart($userId, $productId, $quantity)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            $cart = $this->cartRepository->createCart($userId);
        }

        $cartItem = $this->cartItemRepository->findItem($cart->id, $productId);
        if ($cartItem) {
            return $this->cartItemRepository->updateItem($cart->id, $productId, $cartItem->quantity + $quantity);
        }
        return $this->cartItemRepository->addItem($cart->id, $productId, $quantity);
    }

    public function updateProductInCart($userId, $productId, $quantity)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            return null;
        }
        return $this->cartItemRepository->updateItem($cart->id, $productId, $quantity);
    }

    public function removeProductFromCart($userId, $productId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            return false;
        }
        return $this->cartItemRepository->removeItem($cart->id, $productId);
    }

    public function clearCart($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            return false;
        }
        return $this->cartItemRepository->clearItems($cart->id);
    }
}
