<?php

namespace App\Services\SnappPay;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;

class SnappPayService
{
    public function auth()
    {
        $client_id = config("gateway.snappPay.client_id");
        $client_secret = config("gateway.snappPay.client_secret");
        $username = config("gateway.snappPay.username");
        $password = config("gateway.snappPay.password");
        $url = config("gateway.snappPay.base_url");
        $basicAuth = base64_encode("{$client_id}:{$client_secret}");

        $response = Http::withHeaders([
            'Authorization' => "Basic {$basicAuth}",
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])
            ->asForm() // همان --data-urlencode
            ->post("{$url}/api/online/v1/oauth/token", [
                'grant_type' => 'password',
                'scope' => 'online-merchant',
                'username' => $username,
                'password' => $password,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => $response->status(),
            'message' => $response->body(),
        ];
    }

    public function eligible($price)
    {
        $auth = $this->auth();
        $access_token = $auth["access_token"];
        $url = config("gateway.snappPay.base_url");
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])
            ->asForm() // همان --data-urlencode
            ->get("{$url}/api/online/offer/v1/eligible?amount=$price");

        return $response->json();

    }

    public function request($orderId, $orderItems, $amount)
    {

        $order = Order::find($orderId);
        $cartItems = [];
        $sumCartPrice = 0;
        $sumOff = 0;
        foreach ($orderItems as $item) {
            $object = new \stdClass();
            $object->id = $item->product_color_id;
            $object->amount = $item->final_price * 10;
            $object->name = $item->product->name;
            $object->count = $item->count;
            $object->commissionType = 10500;
            $object->category = $item->product->categories[0]->name;
            $cartItems[] = $object;
            $sumCartPrice += ($item->final_price) * $item->count * 10;
        }

        $mobile = $order->orderInfo->mobile;
        $mobile = preg_replace('/^0/', '+98', $mobile);

        $data = [
            "amount" => $amount,
            "discountAmount" => $order->off * 10,
            "externalSourceAmount" => $order->use_wallet_price * 10,
            "mobile" => $mobile,
            "paymentMethodTypeDto" => "INSTALLMENT",
            "returnURL" => config("gateway.snappPay.order_callback_url"),
            "transactionId" => (string)$orderId,
            "cartList" => [
                [
                    "cartId" => $orderId,
                    "isShipmentIncluded" => true,
                    "isTaxIncluded" => true,
                    "shippingAmount" => ($order->delivery_price) * 10,
                    "taxAmount" => 0,
                    "totalAmount" => $sumCartPrice + ($order->delivery_price * 10),
                    "cartItems" => $cartItems
                ]
            ]
        ];

        dd($data);
        $auth = $this->auth();
        $access_token = $auth["access_token"];
        $url = config("gateway.snappPay.base_url");

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Content-Type' => 'application/json',
        ])
            ->post("{$url}/api/online/payment/v1/token", $data);


        if ($response->successful()) {
            $res = $response->json();
            $paymentToken = $res["response"]["paymentToken"];
            $order->payment_token = $paymentToken;
            $order->save();
            return $res;
        }

        return [
            'error' => $response->status(),
            'message' => $response->body(),
        ];
    }

    public function verify($paymentToken)
    {
        $data = [
            "paymentToken" => $paymentToken,
        ];

        $auth = $this->auth();
        $access_token = $auth["access_token"];
        $url = config("gateway.snappPay.base_url");

        $response = Http::timeout(60)->withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Content-Type' => 'application/json',
        ])
            ->post("{$url}/api/online/payment/v1/verify", $data);

        if ($response->successful()) {
            return $response->json();
        }

        if ($response->requestTimeout()) {
            $status = $this->getPaymentStatus($paymentToken);
            if ($status["successful"]) {
                if ($status["response"]["status"] == "VERIFY") {
                    return $status;
                } else if ($status["response"]["status"] == "PENDING") {
                    $auth = $this->auth();
                    $access_token = $auth["access_token"];
                    $url = config("gateway.snappPay.base_url");

                    $response = Http::timeout(60)->withHeaders([
                        'Authorization' => "Bearer {$access_token}",
                        'Content-Type' => 'application/json',
                    ])
                        ->post("{$url}/api/online/payment/v1/settle", $data);

                    if ($response->successful()) {
                        return $response->json();
                    }
                    return [
                        'error' => $response->status(),
                        'message' => $response->body(),
                    ];
                }
            }
        }


        return [
            'error' => $response->status(),
            'message' => $response->body(),
        ];
    }

    public function getPaymentStatus($paymentToken)
    {
        $auth = $this->auth();
        $access_token = $auth["access_token"];
        $url = config("gateway.snappPay.base_url");

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Content-Type' => 'application/json',
        ])
            ->get("{$url}/api/online/payment/v1/status?paymentToken=$paymentToken");

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => $response->status(),
            'message' => $response->body(),
        ];
    }

    public function settle($paymentToken)
    {

        $data = [
            "paymentToken" => $paymentToken,
        ];

        $auth = $this->auth();
        $access_token = $auth["access_token"];
        $url = config("gateway.snappPay.base_url");

        $response = Http::timeout(60)->withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Content-Type' => 'application/json',
        ])
            ->post("{$url}/api/online/payment/v1/settle", $data);

        if ($response->successful()) {
            return $response->json();
        }

        if ($response->requestTimeout()) {
            $status = $this->getPaymentStatus($paymentToken);
            if ($status["successful"]) {
                if ($status["response"]["status"] == "SETTLE") {
                    return $status;
                } else if ($status["response"]["status"] == "VERIFY") {
                    $auth = $this->auth();
                    $access_token = $auth["access_token"];
                    $url = config("gateway.snappPay.base_url");

                    $response = Http::timeout(60)->withHeaders([
                        'Authorization' => "Bearer {$access_token}",
                        'Content-Type' => 'application/json',
                    ])
                        ->post("{$url}/api/online/payment/v1/settle", $data);

                    if ($response->successful()) {
                        return $response->json();
                    }
                    return [
                        'error' => $response->status(),
                        'message' => $response->body(),
                    ];

                }
            }
        }
        return [
            'error' => $response->status(),
            'message' => $response->body(),
        ];
    }

    public function cancel($orderId)
    {
        $order = Order::find($orderId);

        $data = [
            "paymentToken" => $order->payment_token,
        ];

        $auth = $this->auth();
        $access_token = $auth["access_token"];
        $url = config("gateway.snappPay.base_url");

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Content-Type' => 'application/json',
        ])
            ->post("{$url}/api/online/payment/v1/cancel", $data);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => $response->status(),
            'message' => $response->body(),
        ];
    }

}
