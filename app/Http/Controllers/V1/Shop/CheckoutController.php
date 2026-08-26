<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Checkout\SetDeliveryMethodRequest;
use App\Http\Requests\Shop\Checkout\SetPaymentMethodRequest;
use App\Http\Resources\Checkout\CheckoutResource;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Services\Cart\CartServiceInterface;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Checkout\ShippingMethodResolver;
use App\Services\Delivery\DeliveryServiceInterface;
use App\Services\Tapin\CheckPrice;
use App\Services\Tapin\TapinService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Delivery\DeliveryResource;

class CheckoutController extends Controller
{
    public function __construct
    (
        private readonly CheckoutServiceInterface   $checkoutService,
        private readonly CartServiceInterface       $cartService,
        private readonly CartItemServiceInterface   $cartItemService,
        private readonly DeliveryServiceInterface   $deliveryService,
        private readonly AddressRepositoryInterface $addressRepository,
        private readonly CheckPrice                 $checkPrice,
        private readonly TapinService               $tapinService,
        private readonly ShippingMethodResolver     $shippingMethodResolver,
    )
    {
    }

    public function getShippingMethods()
    {
        $userId = Auth::user()->id;
        $cartItems = $this->cartService->getCartItems($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        $cartPrices = $this->cartItemService->calculatePrice($cartItems);

        return $this->dataResponseCollection(DeliveryResource::collection(
            $this->shippingMethodResolver->resolve($cartItems, $address, $cartPrices["totalItemPrice"])
        ));
    }

    public function getShippingMethods2()
    {
        return $this->dataResponseCollection(DeliveryResource::collection($this->deliveryService->getActives()));
    }

    public function checkoutOrder()
    {
        return $this->dataResponse(new CheckoutResource($this->checkoutService->checkoutOrder(Auth::user()->id)));
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
        $this->checkoutService->gatewayCheckout(Auth::user()->id);
    }

    public function setDeliveryMethod(SetDeliveryMethodRequest $request)
    {
        $this->cartService->setDeliveryMethod(Auth::user()->id, $request->get("delivery_id"));
        return __('action.select', ['attr' => __("attr.delivery_method")]);

    }

    public function setPaymentMethod(SetPaymentMethodRequest $request)
    {
        $this->cartService->setPaymentMethod(Auth::user()->id, $request->get("gateway_id"));
        return __('action.select', ['attr' => __("attr.payment_method")]);

    }
}
