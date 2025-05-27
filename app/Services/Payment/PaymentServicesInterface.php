<?php

namespace App\Services\Payment;

interface PaymentServicesInterface
{
    public function request($userId);
    public function verifyPayment($request);
    public function onHoldOrderRequest($id, $userId);
    public function verifyOrderByWallet($userId);

}
