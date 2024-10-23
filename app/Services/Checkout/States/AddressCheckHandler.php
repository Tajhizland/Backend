<?php

namespace App\Services\Checkout\States;

use App\Exceptions\BreakException;
use App\Models\Cart;
use App\Models\CartItem;
use App\Repositories\Address\AddressRepositoryInterface;
 use Illuminate\Support\Facades\Lang;

class AddressCheckHandler implements CheckoutHandlerInterface
{
    public function __construct(private AddressRepositoryInterface $addressRepository)
    {
    }

    private ?CheckoutHandlerInterface $nextHandler = null;

    public function setNext(CheckoutHandlerInterface $handler): void
    {
        $this->nextHandler = $handler;
    }

    public function handle(Cart $cart,   $cartItem)
    {
        $address = $this->addressRepository->findUserAddress($cart->user_id);
        if (!$address || !$address->city_id || !$address->province_id ||  !$address->tell || !$address->mobile || !$address->zip_code || !$address->address) {
            throw  new BreakException(Lang::get("exceptions.address_not_find"));
        }
        if ($this->nextHandler) {
            return $this->nextHandler->handle($cart,  $cartItem);
        }
        return true;
    }
}
