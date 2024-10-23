<?php

namespace App\Services\Checkout\States;

use App\Enums\DeliveryStatus;
use App\Exceptions\BreakException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\Delivery\DeliveryRepositoryInterface;

class DeliveryCheckHandler implements CheckoutHandlerInterface
{
    private ?CheckoutHandlerInterface $nextHandler = null;

    public function __construct(private DeliveryRepositoryInterface $deliveryRepository)
    {
    }

    public function setNext(CheckoutHandlerInterface $handler): void
    {
        $this->nextHandler = $handler;
    }

    public function handle(Cart $cart,   $cartItem)
    {
        if (!$cart->delivery_method)
            throw  new  BreakException(\Lang::get("exceptions.delivery_not_find"));
        $deliveryMethod = $this->deliveryRepository->findOrFail($cart->delivery_method);
        if ($deliveryMethod->status == DeliveryStatus::DeActive->value)
            throw  new  BreakException(\Lang::get("exceptions.delivery_not_find"));
        if ($this->nextHandler) {
            return $this->nextHandler->handle($cart, $cartItem);
        }
        return true;
    }
}
