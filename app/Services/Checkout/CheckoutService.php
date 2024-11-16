<?php

namespace App\Services\Checkout;

use App\Repositories\Address\AddressRepositoryInterface;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\Repositories\Delivery\DeliveryRepositoryInterface;
use App\Repositories\Gateway\GatewayRepositoryInterface;
use App\Services\Checkout\States\AddressCheckHandler;
use App\Services\Checkout\States\CartCheckHandler;
use App\Services\Checkout\States\DeliveryCheckHandler;
use App\Services\Checkout\States\GatewayCheckHandler;

class CheckoutService implements CheckoutServiceInterface
{
    public function __construct(
        private DeliveryRepositoryInterface $deliveryRepository,
        private GatewayRepositoryInterface  $gatewayRepository,
        private AddressRepositoryInterface  $addressRepository,
        private CartRepositoryInterface     $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository,
        private AddressCheckHandler         $addressCheckHandler,
        private CartCheckHandler            $cartCheckHandler,
        private DeliveryCheckHandler        $deliveryCheckHandler,
        private GatewayCheckHandler         $gatewayCheckHandler,
    )
    {

    }

    public function checkoutOrder($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItem = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->cartCheckHandler->handle($cart, $cartItem);
        $deliveries = $this->deliveryRepository->getActiveDelivery();
        $address = $this->addressRepository->findActiveByUserId($userId);
        $gateway = $this->gatewayRepository->findActiveGateway();
        return
            [
                "cartItem" => $cartItem,
                "deliveries" => $deliveries,
                "address" => $address,
                "gateway" => $gateway,
            ];
    }

    public function deliveryCheckout($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItem = $this->cartItemRepository->getItemsByCartId($cart->id);
        $this->cartCheckHandler->handle($cart, $cartItem);
        return $this->deliveryRepository->getActiveDelivery();
    }

    public function addressCheckout($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItem = $this->cartItemRepository->getItemsByCartId($cart->id);

        $this->cartCheckHandler->setNext($this->deliveryCheckHandler);
        $this->cartCheckHandler->handle($cart, $cartItem);

        return $this->addressRepository->findActiveByUserId($userId);
    }

    public function gatewayCheckout($userId)
    {
        $cart = $this->cartRepository->getCartByUserId($userId);
        $cartItem = $this->cartItemRepository->getItemsByCartId($cart->id);

        $this->cartCheckHandler->setNext($this->deliveryCheckHandler);
        $this->deliveryCheckHandler->setNext($this->addressCheckHandler);
        $this->cartCheckHandler->handle($cart, $cartItem);

        return $this->gatewayRepository->findActiveGateway();
    }

    public function finalCheckout($cart, $cartItems)
    {
        $this->cartCheckHandler->setNext($this->deliveryCheckHandler);
        $this->deliveryCheckHandler->setNext($this->addressCheckHandler);
         $this->addressCheckHandler->setNext($this->gatewayCheckHandler);
        $this->cartCheckHandler->handle($cart, $cartItems);
    }

    public function checkoutIndex($userId)
    {
        // TODO: Implement checkoutIndex() method.
    }
}
