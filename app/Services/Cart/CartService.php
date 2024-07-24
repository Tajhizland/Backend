<?php

namespace App\Services\Cart;

use App\Exceptions\BreakException;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use Illuminate\Support\Facades\Lang;

class CartService implements CartServiceInterface
{
    public function __construct
    (
        private CartRepositoryInterface         $cartRepository,
        private CartItemRepositoryInterface     $cartItemRepository,
        private ProductColorRepositoryInterface $productColorRepository
    )
    {
    }


    public function getCartItems($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            $cart = $this->cartRepository->createCart($userId);
        }
        return $this->cartItemRepository->getItemsByCartId($cart->id);
    }

    public function addProductToCart($userId, $productColorId, $quantity)
    {
        $productColor = $this->productColorRepository->findOrFail($productColorId);

        $cart = $this->cartRepository->getCartByUserId($userId) ?: $this->cartRepository->createCart($userId);

        $cartItem = $this->cartItemRepository->findItem($cart->id, $productColorId);

        $totalQuantity = $cartItem ? $quantity + $cartItem->count : $quantity;

        if ($productColor->invoice->stock < $totalQuantity) {
            throw new BreakException(Lang::get("exceptions.un-stock"));
        }
        $cartItem
            ? $this->cartItemRepository->updateItem($cart->id, $productColorId, $totalQuantity)
            : $this->cartItemRepository->addItem($cart->id, $productColorId, $quantity);

        return $cartItem;
    }

    public function increaseProductInCart($userId, $productColorId)
    {
        $productColor = $this->productColorRepository->findOrFail($productColorId);
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            throw new BreakException(Lang::get("exceptions.cart_not_find"));
        }
        $cartItem = $this->cartItemRepository->findItem($cart->id, $productColorId);
        if (!$cartItem) {
            throw new BreakException(Lang::get("exceptions.unavailable_product_in_cart"));
        }
        if ($productColor->invoice->stock < $cartItem->count + 1) {
            throw new BreakException(Lang::get("exceptions.un-stock"));
        }
        return $this->cartItemRepository->increment($cartItem);

    }

    public function decreaseProductInCart($userId, $productColorId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            throw new BreakException(Lang::get("exceptions.cart_not_find"));
        }
        $cartItem = $this->cartItemRepository->findItem($cart->id, $productColorId);
        if (!$cartItem) {
            throw new BreakException(Lang::get("exceptions.unavailable_product_in_cart"));
        }
        if ($cartItem->count - 1 == 0) {
            return $this->cartItemRepository->removeItem($cart->id, $productColorId);
        }
        return $this->cartItemRepository->decrement($cartItem);
    }

    public function removeProductFromCart($userId, $productColorId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            return false;
        }
        return $this->cartItemRepository->removeItem($cart->id, $productColorId);
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
