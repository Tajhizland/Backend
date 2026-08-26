<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Cart\AddToCartRequest;
use App\Http\Requests\Shop\Cart\MergeCartRequest;
use App\Http\Requests\Shop\Cart\UpdateCartItemRequest;
use App\Services\Cart\CartServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CartItem\CartItemResource;

class CartController extends Controller
{
    public function __construct(private readonly CartServiceInterface $cartService)
    {
    }

    public function get()
    {
        $cart = $this->cartService->getCartItems(Auth::user()->id);
        return $this->dataResponseCollection(CartItemResource::collection($cart));
    }

    public function addToCart(AddToCartRequest $request)
    {
        $this->cartService->addProductToCart(Auth::user()->id, $request->get("productColorId"), $request->get("count"), $request->get("guaranty_id"));
        return $this->successResponse(__("action.add_to",["attr"=>__("attr.product") , "to"=>__("attr.cart")]));
    }

    public function merge(MergeCartRequest $request)
    {
        $cart = $this->cartService->mergeCart(Auth::user()->id, $request->get("items"));
        return $this->dataResponseCollection(CartItemResource::collection($cart), __("action.update", ["attr" => __("attr.cart")]));
    }

    public function removeItem(UpdateCartItemRequest $request)
    {
        $this->cartService->removeProductFromCart(Auth::user()->id, $request->get("productColorId"), $request->get("guaranty_id"));
        return $this->successResponse(__("action.remove_from",["attr"=>__("attr.product") , "from"=>__("attr.cart")]));
     }

    public function increase(UpdateCartItemRequest $request)
    {
        $this->cartService->increaseProductInCart(Auth::user()->id, $request->get("productColorId"), $request->get("guaranty_id"));
        return $this->successResponse(__("action.update",["attr"=>__("attr.cart")]));
    }

    public function decrease(UpdateCartItemRequest $request)
    {
        $this->cartService->decreaseProductInCart(Auth::user()->id, $request->get("productColorId"), $request->get("guaranty_id"));
        return $this->successResponse(__("action.update",["attr"=>__("attr.cart")]));
    }

    public function clearAll()
    {
        $this->cartService->clearCart(Auth::user()->id);
        return $this->successResponse(__("action.clear",["attr"=>__("attr.cart")]));
    }
}
