<?php

namespace App\Services\Payment\Gateways;

interface GatewaysInterface
{
    public function request($amount, $orderId);
    public function verify($trackId);
    public function callbackParams($request);
}
