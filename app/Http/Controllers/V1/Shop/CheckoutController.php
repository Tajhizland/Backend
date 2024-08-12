<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Checkout\SetDeliveryMethodRequest;
use App\Http\Requests\V1\Shop\Checkout\SetPaymentMethodRequest;
use App\Http\Resources\V1\Checkout\CheckoutResource;
use App\Services\Cart\CartServiceInterface;
use App\Services\Checkout\CheckoutServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CheckoutController extends Controller
{
    public function __construct
    (
        private  CheckoutServiceInterface $checkoutService,
        private  CartServiceInterface $cartService,
    ) { }
    public function checkoutOrder()
    {
        return $this->dataResponse(new CheckoutResource($this->checkoutService->checkoutOrder(Auth::user()->id))) ;
    }

    public function deliveryCheckout()
    {
        $this->checkoutService->deliveryCheckout(Auth::user()->id);
    }
    public function addressCheckout()
    {
        $this->checkoutService->addressCheckout(Auth::user()->id);
    }
    public function gatewayCheckout()
    {
        $this->checkoutService->gatewayCheckout(Auth::user()->id );
    }

    public function setDeliveryMethod(SetDeliveryMethodRequest $request)
    {
        $this->cartService->setDeliveryMethod(Auth::user()->id,$request->get("delivery_id"));
        return Lang::get('action.select', ['attr' => Lang::get("attr.delivery_method")]);

    }
    public function setPaymentMethod(SetPaymentMethodRequest $request)
    {
        $this->cartService->setPaymentMethod(Auth::user()->id ,$request->get("gateway_id"));
        return Lang::get('action.select', ['attr' => Lang::get("attr.payment_method")]);

    }
}
