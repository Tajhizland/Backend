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

    public function addProductToCart($userId, $productColorId, $quantity,$guarantyId)
    {
        $productColor = $this->productColorRepository->findOrFail($productColorId);

        $cart = $this->cartRepository->getCartByUserId($userId) ?: $this->cartRepository->createCart($userId);

        $cartItem = $this->cartItemRepository->findItem($cart->id, $productColorId,$guarantyId);

        $totalQuantity = $cartItem ? $quantity + $cartItem->count : $quantity;

        if ($productColor->stock->stock < $totalQuantity) {
            throw new BreakException(Lang::get("exceptions.un-stock"));
        }
        $cartItem
            ? $this->cartItemRepository->updateItem($cart->id, $productColorId, $totalQuantity,$guarantyId)
            : $this->cartItemRepository->addItem($cart->id, $productColorId, $quantity,$guarantyId);

        return $cartItem;
    }

    public function increaseProductInCart($userId, $productColorId,$guarantyId)
    {
        $productColor = $this->productColorRepository->findOrFail($productColorId);
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            throw new BreakException(Lang::get("exceptions.cart_not_find"));
        }
        $cartItem = $this->cartItemRepository->findItem($cart->id, $productColorId,$guarantyId);
        if (!$cartItem) {
            throw new BreakException(Lang::get("exceptions.unavailable_product_in_cart"));
        }
        if ($productColor->stock->stock < $cartItem->count + 1) {
            throw new BreakException(Lang::get("exceptions.un-stock"));
        }
        return $this->cartItemRepository->increment($cartItem);

    }

    public function decreaseProductInCart($userId, $productColorId,$guarantyId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            throw new BreakException(Lang::get("exceptions.cart_not_find"));
        }
        $cartItem = $this->cartItemRepository->findItem($cart->id, $productColorId,$guarantyId);
        if (!$cartItem) {
            throw new BreakException(Lang::get("exceptions.unavailable_product_in_cart"));
        }
        if ($cartItem->count - 1 == 0) {
            return $this->cartItemRepository->removeItem($cart->id, $productColorId,$guarantyId);
        }
        return $this->cartItemRepository->decrement($cartItem);
    }

    public function removeProductFromCart($userId, $productColorId,$guarantyId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            return false;
        }
        return $this->cartItemRepository->removeItem($cart->id, $productColorId,$guarantyId);
    }

    public function clearCart($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        if (!$cart) {
            return false;
        }
        return $this->cartItemRepository->clearItems($cart->id);
    }

    public function setDeliveryMethod($userId, $delivery_method)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        return $this->cartRepository->setDeliveryMethod($cart, $delivery_method);
    }

    public function setPaymentMethod($userId, $payment_method)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        return $this->cartRepository->setPaymentMethod($cart, $payment_method);
    }
}
