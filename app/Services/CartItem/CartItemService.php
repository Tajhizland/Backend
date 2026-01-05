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
use App\Services\Guaranty\GuarantyServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

class CartItemService implements CartItemServiceInterface
{
    public function __construct
    (
        private PriceRepositoryInterface        $priceRepository,
        private ProductColorRepositoryInterface $productColorRepository,
        private OrderItemRepositoryInterface    $orderItemRepository,
        private CartRepositoryInterface         $cartRepository,
        private GuarantyServiceInterface        $guarantyService,
        private CartItemRepositoryInterface     $cartItemRepository
    )
    {
    }

    public function calculatePrice($cartItems): array
    {
        $itemsPrice = 0;
        $totalItemPrice = 0;
        $maxDeliveryDelay = 0;
        foreach ($cartItems as $cartItem) {
            $price = $this->priceRepository->findByProductColorId($cartItem->product_color_id);
            $guarantyPrice = 0;
            if ($cartItem->guaranty_id) {
                $guaranty = $this->guarantyService->findById($cartItem->guaranty_id);
                if (!$guaranty->free) {
                    $guarantyPrice = $this->guarantyService->calculatePrice($price->price);
                }
            }
            $itemsPrice += $price->price * $cartItem->count;

            $productColor=$this->productColorRepository->findOrFail($cartItem->product_color_id);
            $discountItem = $productColor->activeDiscountItem->first();

            if ($discountItem && $discountItem->discount_price && $discountItem->discount_price != 0) {
                $totalItemPrice +=( $discountItem->discount_price + $guarantyPrice ) * $cartItem->count;
            } else {
                $totalItemPrice += ($price->price + $guarantyPrice)* $cartItem->count ;
            }

            if ($cartItem->productColor->delivery_delay > $maxDeliveryDelay) {
                $maxDeliveryDelay = $cartItem->productColor->delivery_delay;
            }
        }
        return [
            "itemsPrice" => $itemsPrice,
            "maxDeliveryDelay" => $maxDeliveryDelay,
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
            $discountItem = $productColor->activeDiscountItem->first();
            $price = $productColor->price;
            $guarantyPrice = 0;
            $discount = 0;
            if ($cartItem->guaranty_id) {
                $guaranty = $this->guarantyService->findById($cartItem->guaranty_id);
                if (!$guaranty->free) {
                    $guarantyPrice = $this->guarantyService->calculatePrice($price->price);
                }
            }
            if ($discountItem && $discountItem->discount_price && $discountItem->discount_price != 0) {
                $finalPrice = ($discountItem->discount_price + $guarantyPrice);
                $discount = $price->price - $discountItem->discount_price;
            } else {
                $finalPrice = ($price->price + $guarantyPrice);
            }
            $this->orderItemRepository->createOrderItem($orderId, $productColor->product_id, $productColor->id, $cartItem->count, $price->price, $discount, $finalPrice, $cartItem->guaranty_id, $guarantyPrice);
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
