<?php

namespace App\Services\DigiPay;

class DigiPayService
{
    public function login()
    {

        $client_id = config("gateway.digipay.client_id");
        $client_secret = config("gateway.digipay.client_secret");
        $username = config("gateway.digipay.username");
        $password = config("gateway.digipay.password");
        $basicAuth = base64_encode("{$client_id}:{$client_secret}");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mydigipay.com/digipay/api/oauth/token');
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
            "callbackUrl" => config("gateway.digipay.ORDER_CALLBACK_URL"),
            "basketDetailsDto" => [
                "items" => $orderItemsDto,
                "basketId" => $orderId
            ],
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mydigipay.com/digipay/api/tickets/business?type=11');
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


        return json_decode($response);
    }


    public function verify($trackingCode, $orderId)
    {

        $auth = $this->login();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mydigipay.com/digipay/api/purchases/verify?type=5');
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
}
