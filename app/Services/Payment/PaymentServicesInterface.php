<?php

namespace App\Services\Payment;

interface PaymentServicesInterface
{
    public function request($userId, $useWallet, $shippingMethod, $code = null , $shippingPrice=0, $gateway=1);
    public function verifyPayment2($request);

    public function verifyPayment($request);

    public function onHoldOrderRequest($id, $userId);

    public function onHoldOrderVerifyByWallet($id, $userId);

    public function verifyOrderByWallet($userId);

}
