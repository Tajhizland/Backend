<?php

namespace App\Services\Checkout\States;

use App\Enums\ProductColorStatus;
use App\Enums\ProductStatus;
use App\Exceptions\BreakException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductColor;
use App\Repositories\CartItem\CartItemRepository;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepository;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use App\Services\Checkout\States\CheckoutHandlerInterface;
use Illuminate\Support\Facades\Lang;

class CartCheckHandler implements CheckoutHandlerInterface
{

    private ?CheckoutHandlerInterface $nextHandler = null;

    public function __construct(private CartItemRepositoryInterface $cartItemRepository ,private ProductColorRepositoryInterface $productColorRepository)
    {
    }


    public function setNext(CheckoutHandlerInterface $handler): void
    {
        $this->nextHandler=$handler;
    }

    public function handle(Cart $cart ,   $cartItem)
    {
         $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
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
        if($this->nextHandler)
        {
            return $this->nextHandler->handle($cart ,$cartItem);
        }
        return  true;
    }
}
