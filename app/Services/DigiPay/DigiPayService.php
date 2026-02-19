<?php

namespace App\Services\DigiPay;

use App\Exceptions\BreakException;

class DigiPayService
{
    public function login()
    {

        $client_id = config("Gateway.digipay.client_id");
        $client_secret = config("Gateway.digipay.client_secret");
        $username = config("Gateway.digipay.username");
        $password = config("Gateway.digipay.password");
        $basicAuth = base64_encode("{$client_id}:{$client_secret}");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config("Gateway.digipay.API_BASE_URL") . '/oauth/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . $basicAuth,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'username' => $username,
            'password' => $password,
            'grant_type' => 'password',
        ]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }

    public function request($amount,
                            $mobile,
                            $orderId,
                            $orderItems)
    {
        $auth = $this->login();
        $orderItemsDto = [];
        foreach ($orderItems as $item) {
            $object = new \stdClass();
            $object->sellerId = "1";
            $object->supplierId = "1";
            $object->productCode = $item->product->id;
            $object->brand = "1";
            $object->productType = 2;
            $object->count = $item->count;
            $object->categoryId = "Mobile";
            $orderItemsDto[] = $object;
        }
        $data = [
            "cellNumber" => $mobile,
            "amount" => $amount * 10,
            "providerId" => $orderId,
            "callbackUrl" => config("Gateway.digipay.ORDER_CALLBACK_URL"),
            "basketDetailsDto" => [
                "items" => $orderItemsDto,
                "basketId" => $orderId
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config("Gateway.digipay.API_BASE_URL") . '/tickets/business?type=11');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Agent: WEB',
            'Digipay-Version: 2022-02-02',
            'Authorization: Bearer ' . $auth->access_token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);
        curl_close($ch);


        try {
            $response = json_decode($response);
            return $response->redirectUrl;
        } catch (\Exception $e) {
            throw new BreakException(json_encode($response));
        }
    }


    public function verify($trackingCode, $orderId)
    {

        $auth = $this->login();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config("Gateway.digipay.API_BASE_URL") . '/purchases/verify?type=5');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $auth->access_token,
            'Content-Type: application/json',
        ]);
        $data = [
            "trackingCode" => $trackingCode,
            "providerId" => $orderId
        ];
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        curl_close($ch);
        return json_decode($response);

    }

    public function callbackParams($request)
    {
        $amount = $request->get("amount");
        $result = $request->get("result");
        $providerId = $request->get("providerId");
        $trackingCode = $request->get("trackingCode");
        if ($result != "SUCCESS" || !$amount || !$providerId || !$trackingCode) {
            throw new BreakException();
        }

        $result = new \stdClass();
        $result->trackId = $trackingCode;
        $result->orderId = $providerId;
        return $result;
    }

}
