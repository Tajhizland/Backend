<?php

namespace App\Services\Payment\Gateways\Strategy;

use App\Services\Gateway\GatewayServiceInterface;
use App\Services\Payment\Gateways\GatewaysInterface;
use App\Services\Payment\Gateways\Zibal\ZibalService;

class GatewayStrategyServices implements GatewayStrategyServicesInterface
{
    public function __construct
    (
        private ZibalService            $zibalService,
        private GatewayServiceInterface $gatewayService,
    )
    {
    }

    public function strategy(): GatewaysInterface
    {
        $activeGateway = $this->gatewayService->findActiveGateway();
        $activeGatewayId = $activeGateway->id;
        switch ($activeGatewayId) {
            case 1:
                return $this->zibalService;
            default :
                return $this->zibalService;
        }
    }
}
