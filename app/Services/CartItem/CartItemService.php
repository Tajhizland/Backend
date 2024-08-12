<?php

namespace App\Services\CartItem;

use App\Enums\ProductColorStatus;
use App\Enums\ProductStatus;
use App\Exceptions\BreakException;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use Illuminate\Support\Facades\Lang;

class CartItemService implements CartItemServiceInterface
{
    public function __construct
    (
        private PriceRepositoryInterface        $priceRepository,
        private ProductColorRepositoryInterface $productColorRepository,
        private OrderItemRepositoryInterface    $orderItemRepository,
        private CartRepositoryInterface         $cartRepository,
        private CartItemRepositoryInterface     $cartItemRepository
    )
    {
    }

    public function calculatePrice($cartItems): array
    {
        $itemsPrice = 0;
        $itemsDiscount = 0;
        $totalItemPrice = 0;
        foreach ($cartItems as $cartItem) {
            $price = $this->priceRepository->findByProductColorId($cartItem->product_color_id);
            $itemsPrice += $price->price * $cartItem->count;
            $itemsDiscount += ($price->price * ($price->discount / 100)) * $cartItem->count;
            $totalItemPrice += ($price->price - ($price->price * ($price->discount / 100))) * $cartItem->count;
        }
        return [
            "itemsPrice" => $itemsPrice,
            "itemsDiscount" => $itemsDiscount,
            "totalItemPrice" => $totalItemPrice,
        ];
    }

    public function checkLimit($cartItems): bool
    {
        foreach ($cartItems as $cartItem) {
            $productColor = $this->productColorRepository->findOrFail($cartItem->product_color_id);
            if ($productColor->status == ProductColorStatus::Limit->value) {
                return true;
            }
        }
        return false;
    }

    public function checkAllow($cartItems): bool
    {
        foreach ($cartItems as $cartItem) {
            $productColor = $this->productColorRepository->findOrFail($cartItem->product_color_id);
            if
            ($productColor->stock->stock < $cartItem->count) {
                throw  new BreakException(Lang::get("exceptions.product_un_stock_in_cart"));
            }
            if
            ($productColor->product->status == ProductStatus::DeActive->value) {
                throw  new BreakException(Lang::get("exceptions.product_deactive_in_cart"));
            }
            if
            ($productColor->status == ProductColorStatus::DeActive->value) {
                throw  new BreakException(Lang::get("exceptions.product_deactive_in_cart"));
            }
        }
        return true;
    }

    public function convertCartItemToOrderItem($cartItems, $orderId): bool
    {
        foreach ($cartItems as $cartItem) {
            $productColor = $this->productColorRepository->findOrFail($cartItem->product_color_id);
            $price = $productColor->price;

            $totalPrice = $price->price * $cartItem->count;
            $totalDiscount = ($price->price * ($price->discount / 100)) * $cartItem->count;
            $finalPrice = ($price->price - ($price->price * ($price->discount / 100))) * $cartItem->count;
            $unitPrice = ($price->price - ($price->price * ($price->discount / 100)));
            $unitDiscount = ($price->price - ($price->price * ($price->discount / 100)));

            $this->orderItemRepository->createOrderItem($orderId, $productColor->product_id, $productColor->id, $cartItem->count, $totalPrice, $totalDiscount, $finalPrice, $unitPrice, $unitDiscount);
        }
        return true;
    }

    public function checkoutCart($userId): bool
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->checkAllow($cartItems);
        return true;
    }
}
