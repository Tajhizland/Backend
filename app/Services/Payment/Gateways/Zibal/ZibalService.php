<?php

namespace App\Services\Payment\Gateways\Zibal;

use App\Exceptions\BreakException;
use App\Services\Payment\Gateways\GatewaysInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

class ZibalService implements GatewaysInterface
{

    public function request($amount, $orderId)
    {
        $parameters = array(
            "merchant" => config("Gateway.zibal.merchant"),
            "callbackUrl" => config("Gateway.zibal.callback_url"),
            "amount" => $amount,
            "orderId" => $orderId,
        );
        $response = $this->callApi(config("Gateway.zibal.request_url"), $parameters);
        if ($response["result"] == 100) {
            return  (config("Gateway.zibal.payment_url") . $response["trackId"]);
        }
        throw  new BreakException(Lang::get("exceptions.gateway_error"));
    }

    public function verify($trackId)
    {
        $parameters = array(
            "merchant" => config("Gateway.zibal.merchant"),
            "trackId" => $trackId,
        );
        $response = $this->callApi(config("Gateway.zibal.verify_url"), $parameters);
        if ($response["result"] == 100) {
            return $response;
        }
        throw  new BreakException(Lang::get("exceptions.gateway_error"));
    }

    public function callbackParams($request)
    {
        $success = $request->get("success");
        $orderId = $request->get("orderId");
        $trackId = $request->get("trackId");
        $status = $request->get("status");
        if (!$status || !$orderId || $success || $trackId)
        {
            throw new BreakException();
        }
        if ($success != 1)
            throw new BreakException();

        return [
            "orderId"=>$orderId,
            "trackId"=>$trackId
        ];
    }

    private function callApi($path, $parameters)
    {
        $url = config("Gateway.zibal.base_url") . $path;
        return Http::post($url, $parameters)->json();
    }
}
