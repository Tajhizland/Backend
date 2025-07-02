<?php

namespace App\Services\Payment;

interface PaymentServicesInterface
{
    public function request($userId , $useWallet);
    public function verifyPayment($request);
    public function onHoldOrderRequest($id, $userId);
    public function onHoldOrderVerifyByWallet($id , $userId);

    public function verifyOrderByWallet($userId);

}
