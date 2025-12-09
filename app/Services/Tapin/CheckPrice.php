<?php

namespace App\Services\Tapin;

use Illuminate\Support\Facades\Http;

class CheckPrice
{
    private static $SHOP_ID = "150bf688-c2ed-4069-ad7b-6acead3da505";
    private static $URL = 'https://api.tapin.ir/api/v2/public/order/post/check-price/';
    private static $TOKEN = "jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiZmJhY2JiMzEtMmVlMC00ZDRlLWE3MGYtZTk1ZWYzN2VhMzUzIiwidXNlcm5hbWUiOiIwOTEyNDEyNDEzMCIsImVtYWlsIjoiZmF0dGFoemFkZWhAZ21haWwuY29tIiwiZXhwIjoyNTY0MDM0MTgzLCJvcmlnX2lhdCI6MTcwMDAzNDE4M30.wbxqBnk8pbHuOgKXEcAUwTRh0Jan-NAc2VIJTojxa9w";

    public static function check($province,$city,$weight,$price,$boxId=4)
    {
        if($weight<50)
            $weight=50;

        $data = [
            "shop_id" => self::$SHOP_ID,
            "address" => " iran",
            "city_code" => $city,
            "province_code" => $province,
            "description" => null,
            "email" => null,
            "employee_code" => "-1",
            "first_name" => "Tajhizland",
            "last_name" => "Tajhizland",
            "mobile" => "09011111111",
            "phone" => null,
            "postal_code" => "1313131313",
            "pay_type" => "1",
            "box_id" =>$boxId,
            "order_type" => "1",
            "package_weight" => 0,
            "products" => [
                [
                    "count" => 1,
                    "discount" => 0,
                    "price" => $price,
                    "title" => "my product",
                    "weight" => $weight,
                    "product_id" => null
                ]
            ],
        ];
       return Http::withToken(self::$TOKEN, "")->post(self::$URL, $data)->json();
    }
}
