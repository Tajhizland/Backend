<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Repositories\Base\BaseRepositoryInterface;

interface CartRepositoryInterface extends BaseRepositoryInterface
{
    public function getCartByUserId($userId);
    public function getCartByOrderId($orderId);
    public function createCart($userId , $payment=null);
    public function changeStatus(Cart $cart,$status);
    public function setDeliveryMethod(Cart $cart, $delivery_method);
    public function setPaymentMethod(Cart $cart, $payment_method);
}
