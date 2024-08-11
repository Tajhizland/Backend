<?php

namespace App\Services\Payment\Gateways\Strategy;

use App\Services\Payment\Gateways\GatewaysInterface;

interface GatewayStrategyServicesInterface
{
    public function strategy():GatewaysInterface;
}
