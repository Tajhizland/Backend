<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
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
//        $this->cartService->decreaseProductInCart(1, 1);

//        $cart = $this->cartService->getCartItems(Auth::user()->id);
//        $this->cartService->clearCart(1);
        $this->cartService->addProductToCart(1, 5116556165, 2);

        $cart = $this->cartService->getCartItems(1);
        return $this->dataResponse($cart);
    }

    public function addToCart(AddToCartRequest $request)
    {
//        $this->cartService->addProductToCart(Auth::user()->id, $request->get("productColorId"), $request->get("count"));
        $this->cartService->addProductToCart(Auth::user()->id, $request->get("productColorId"), $request->get("count"));
        return $this->successResponse(Lang::get("responses.add_to_cart"));
    }

    public function removeItem(UpdateCartItemRequest $request)
    {
        $this->cartService->removeProductFromCart(Auth::user()->id, $request->get("productColorId"));
        return $this->successResponse(Lang::get("responses.remove_product_cart"));
    }

    public function increase(UpdateCartItemRequest $request)
    {
        $this->cartService->increaseProductInCart(Auth::user()->id, $request->get("productColorId"));
        return $this->successResponse(Lang::get("responses.update_product_cart"));
    }

    public function decrease(UpdateCartItemRequest $request)
    {
        $this->cartService->decreaseProductInCart(Auth::user()->id, $request->get("productColorId"));
        return $this->successResponse(Lang::get("responses.update_product_cart"));
    }

    public function clearAll()
    {
        $this->cartService->clearCart(Auth::user()->id);
        return $this->successResponse(Lang::get("responses.remove_cart"));
    }
}
