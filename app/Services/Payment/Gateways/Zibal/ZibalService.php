<?php

namespace App\Services\Payment\Gateways\Zibal;

use App\Exceptions\BreakException;
use App\Services\Payment\Gateways\GatewaysInterface;
use App\Services\RequestLog\RequestLogServiceInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Lang;

class ZibalService implements GatewaysInterface
{
    public function __construct(private readonly RequestLogServiceInterface $requestLogService)
    {
    }

    public function request($amount, $orderId)
    {
        $parameters = array(
            "merchant" => config("Gateway.zibal.merchant"),
            "callbackUrl" => config("Gateway.zibal.callback_url"),
            "amount" => $amount,
            "orderId" => $orderId,
        );
        $response = $this->callApi(config("Gateway.zibal.request_url"), $parameters, 'zibal.request');
        if (($response["result"] ?? null) == 100) {
            return  (config("Gateway.zibal.payment_url") . $response["trackId"]);
        }
        throw  new BreakException(Lang::get("exceptions.gateway_error"));
    }
    public function chargeRequest($amount, $orderId)
    {
        $parameters = array(
            "merchant" => config("Gateway.zibal.merchant"),
            "callbackUrl" => config("Gateway.zibal.callback_charge_url"),
            "amount" => $amount,
            "orderId" => $orderId,
        );
        $response = $this->callApi(config("Gateway.zibal.request_url"), $parameters, 'zibal.charge');
        if (($response["result"] ?? null) == 100) {
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
        $response = $this->callApi(config("Gateway.zibal.verify_url"), $parameters, 'zibal.verify');
         if (($response["result"] ?? null) == 100) {
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
         if (!$status || !$orderId || !$success || !$trackId)
        {
             throw new BreakException();
        }
        if ($success != 1)
            throw new BreakException();

        $result=new \stdClass();
        $result->trackId=$trackId;
        $result->orderId=$orderId;
        return $result;
    }

    private function callApi($path, $parameters, string $title): array
    {
        $url = config("Gateway.zibal.base_url") . $path;
        $loggedRequest = array_replace($parameters, ['merchant' => '***']);
        $loggedRequest['url'] = $url;

        try {
            $response = Http::post($url, $parameters);
        } catch (ConnectionException $exception) {
            $this->requestLogService->log($title, $loggedRequest, [
                'status' => null,
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $body = $response->json();
        $this->requestLogService->log($title, $loggedRequest, [
            'status' => $response->status(),
            'body' => $body ?? $response->body(),
        ]);

        return is_array($body) ? $body : [];
    }
}
