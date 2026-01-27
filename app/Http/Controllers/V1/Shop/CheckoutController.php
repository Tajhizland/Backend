<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Checkout\SetDeliveryMethodRequest;
use App\Http\Requests\V1\Shop\Checkout\SetPaymentMethodRequest;
use App\Http\Resources\V1\Checkout\CheckoutResource;
use App\Http\Resources\V1\Delivery\DeliveryCollection;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Services\Cart\CartServiceInterface;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\Checkout\CheckoutServiceInterface;
use App\Services\Delivery\DeliveryServiceInterface;
use App\Services\Tapin\CheckPrice;
use App\Services\Tapin\TapinService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CheckoutController extends Controller
{
    public function __construct
    (
        private CheckoutServiceInterface   $checkoutService,
        private CartServiceInterface       $cartService,
        private CartItemServiceInterface   $cartItemService,
        private DeliveryServiceInterface   $deliveryService,
        private AddressRepositoryInterface $addressRepository,
        private CheckPrice                 $checkPrice,
        private TapinService               $tapinService,
    )
    {
    }

    public function getShippingMethods()
    {


        $userId = Auth::user()->id;
        $cartItems = $this->cartService->getCartItems($userId);
        $address = $this->addressRepository->findActiveByUserId($userId);

        $size = 10;
        $isPacket = false;
        $isPacketAllow = true;
        $weight = 0;
        $width = 0;
        $height = 0;
        $length = 0;

        $cartPrices = $this->cartItemService->calculatePrice($cartItems);
        $totalItemsPrice = $cartPrices["totalItemPrice"];

        foreach ($cartItems as $item) {
            $product = $item->productColor->product;
            $weight += $product->weight;
            $width += $product->width;
            $height += $product->height;
            $length += $product->length;
            if ($product->is_packet) {
                if ($isPacketAllow) {
                    $isPacket = true;
                    $isPacketAllow = false;
                }
            }
        }

        $boxs = $this->tapinService->getBox();
        $boxs = json_decode(json_encode($boxs));
        $boxs = $boxs->entries->list;
        foreach ($boxs as $box) {
            if ($isPacket) {
                if ($box->pk < 10)
                    continue;
            } else {
                if ($box->pk > 10)
                    continue;
            }
            if ($box->length < $length && $box->width < $width && $box->height < $height) {
                $size = $box->pk;
            }
        }
        if ($size == 10 && $isPacket) {
            foreach ($boxs as $box) {
                if ($box->length < $length && $box->width < $width && $box->height < $height) {
                    $size = $box->pk;
                }
            }
        }
        if ($weight < 50) {
            $weight = 50;
        }
        if ($weight > 30000) {
            $weight = 30000;
        }

        $response = $this->deliveryService->getActives();
        foreach ($response as $delivery) {
            if ($delivery->id == 1) {
                $priceCheck = $this->checkPrice->check($address->province_id, $address->city_id, $weight, $totalItemsPrice, $size);
                $priceCheck = json_decode(json_encode($priceCheck));
                $delivery->price = ceil($priceCheck->entries->total_price / 1000) * 100;
            }
        }
        return $this->dataResponseCollection(new DeliveryCollection($response));
    }

    public function getShippingMethods2()
    {
        return $this->dataResponseCollection(new DeliveryCollection($this->deliveryService->getActives()));
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
        return Lang::get('action.select', ['attr' => Lang::get("attr.delivery_method")]);

    }

    public function setPaymentMethod(SetPaymentMethodRequest $request)
    {
        $this->cartService->setPaymentMethod(Auth::user()->id, $request->get("gateway_id"));
        return Lang::get('action.select', ['attr' => Lang::get("attr.payment_method")]);

    }
}
