<?php

namespace App\Services\Checkout;

use App\Repositories\Base\BaseRepositoryInterface;

interface CheckoutServiceInterface
{
    public function checkoutIndex($userId);
    public function checkoutOrder($userId);
    public function deliveryCheckout($userId);
    public function addressCheckout($userId);
    public function gatewayCheckout($userId);
    public function finalCheckout($cart , $cartItems);
}
