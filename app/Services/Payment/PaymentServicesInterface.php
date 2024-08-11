<?php

namespace App\Services\Payment;

interface PaymentServicesInterface
{
    public function request($userId);
    public function verifyPayment($request);

}
