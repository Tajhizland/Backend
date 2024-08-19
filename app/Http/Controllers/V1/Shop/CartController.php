<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Cart\AddToCartRequest;
use App\Http\Requests\V1\Shop\Cart\UpdateCartItemRequest;
use App\Http\Resources\V1\CartItem\CartItemCollection;
use App\Services\Cart\CartServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CartController extends Controller
{
    public function __construct(private CartServiceInterface $cartService)
    {
    }

    public function get()
    {
        $cart = $this->cartService->getCartItems(Auth::user()->id);
        return $this->dataResponse(new CartItemCollection($cart));
    }

    public function addToCart(AddToCartRequest $request)
    {
        $this->cartService->addProductToCart(Auth::user()->id, $request->get("productColorId"), $request->get("count"));
        return $this->successResponse(Lang::get("action.add_to",["attr"=>Lang::get("attr.product") , "to"=>Lang::get("attr.cart")]));
     }

    public function removeItem(UpdateCartItemRequest $request)
    {
        $this->cartService->removeProductFromCart(Auth::user()->id, $request->get("productColorId"));
        return $this->successResponse(Lang::get("action.remove_from",["attr"=>Lang::get("attr.product") , "from"=>Lang::get("attr.cart")]));
     }

    public function increase(UpdateCartItemRequest $request)
    {
        $this->cartService->increaseProductInCart(Auth::user()->id, $request->get("productColorId"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.cart")]));
    }

    public function decrease(UpdateCartItemRequest $request)
    {
        $this->cartService->decreaseProductInCart(Auth::user()->id, $request->get("productColorId"));
        return $this->successResponse(Lang::get("action.update",["attr"=>Lang::get("attr.cart")]));
    }

    public function clearAll()
    {
        $this->cartService->clearCart(Auth::user()->id);
        return $this->successResponse(Lang::get("action.clear",["attr"=>Lang::get("attr.cart")]));
    }
}
