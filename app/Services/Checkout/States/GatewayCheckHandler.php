<?php

namespace App\Services\Checkout\States;

use App\Enums\GatewayStatus;
use App\Exceptions\BreakException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\Gateway\GatewayRepositoryInterface;

class GatewayCheckHandler implements CheckoutHandlerInterface
{
    public function __construct(private GatewayRepositoryInterface $gatewayRepository)
    {
    }

    private ?CheckoutHandlerInterface $nextHandler = null;

    public
    function setNext(CheckoutHandlerInterface $handler): void
    {
        $this->nextHandler = $handler;
    }

    public
    function handle(Cart $cart, CartItem $cartItem)
    {
        if (!$cart->payment_method)
            throw  new BreakException();
        $paymentMethod = $this->gatewayRepository->findOrFail($cart->payment_method);
        if ($paymentMethod->status == GatewayStatus::DeActive->value) {
            throw  new BreakException();
        }

        if ($this->nextHandler) {
            return $this->nextHandler->handle($cart , $cartItem);
        }
        return true;
    }
}
