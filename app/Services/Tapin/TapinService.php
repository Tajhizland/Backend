<?php

namespace App\Service\Tapin;

use App\Repositories\City\CityRepositoryInterface;
use App\Repositories\TapinCity\TapinCityRepositoryInterface;
use App\Service\Lang\LangService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TapinService implements TapinServiceInterface
{
    protected string $baseUrl = 'https://api.tapin.ir/api/v2/public/order/post/register/';
    protected string $apiToken = 'jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiZmJhY2JiMzEtMmVlMC00ZDRlLWE3MGYtZTk1ZWYzN2VhMzUzIiwidXNlcm5hbWUiOiIwOTEyNDEyNDEzMCIsImVtYWlsIjoiZmF0dGFoemFkZWhAZ21haWwuY29tIiwiZXhwIjoyNTY0MDM0MTgzLCJvcmlnX2lhdCI6MTcwMDAzNDE4M30.wbxqBnk8pbHuOgKXEcAUwTRh0Jan-NAc2VIJTojxa9w';

    public function __construct
    (
        private CityRepositoryInterface      $cityRepository,
        private TapinCityRepositoryInterface $tapinCityRepository
    )
    {
    }

    public function send($order, $boxId, $postStatus, $weight, $part)
    {
        try {
            $data = $this->getData($order, $boxId, $postStatus, $weight, $part);
            $response = Http::withHeaders([
                'Authorization' => $this->apiToken,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, $data);

            if ($response->successful()) {
                return $response->json();
            }
            throw new BadRequestHttpException(json_encode($response));
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    private function getData($order, $boxId, $postStatus, $weight, $part)
    {

        $orderInfo = $order->info;
        $items = [];
        $items["count"] = 1;
        $items["discount"] = 0;
        $items["title"] = "محصولات خرازی";
        $items["weight"] = round($weight);
        $items["price"] = $order->total_items * 10;
        $items["product_id"] = null;

        $city_code = $orderInfo->city_id;
        $city = $this->cityRepository->findOrFail($orderInfo->city_id);
        $tapinCity = $this->tapinCityRepository->findByCity($city_code);
        if (!$tapinCity) {
            $tapinCity = $this->tapinCityRepository->findByProvince($city->parent_province);
            $city_code = $tapinCity->city;
        }

        $address = preg_replace("/<br>|\n/", "", $orderInfo->address);
        $address = LangService::convertArabicToPersian($address);

        $data = [];
        $data["content_type"] = $postStatus;
        $data["register_type"] = 2;
        $data["shop_id"] = "150bf688-c2ed-4069-ad7b-6acead3da505";
        $data["address"] = $address;
        $data["city_code"] = $city_code;
        $data["province_code"] = $city->parent_province;
        $data["description"] = null;
        $data["email"] = null;
        $data["employee_code"] = "-1";
        $data["first_name"] = $orderInfo->fname;
        $data["last_name"] = $orderInfo->lname;
        $data["mobile"] = $orderInfo->mobile;
        $data["phone"] = null;
        $data["postal_code"] = $orderInfo->postal_code;
        $data["pay_type"] = 1;
        $data["order_type"] = 1;
        $data["package_weight"] = 0;
        $data["manual_id"] = $part == 1 ? $order->id : ($order->id . "00000");
        $data["box_id"] = $boxId;
        $data["products"] = [$items];
        return $data;
    }

    //    public function send($data)
//    {
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL,"https://api.tapin.ir/api/v2/public/order/post/register/");
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, [
//            'Authorization: ' . "jwt eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiZmJhY2JiMzEtMmVlMC00ZDRlLWE3MGYtZTk1ZWYzN2VhMzUzIiwidXNlcm5hbWUiOiIwOTEyNDEyNDEzMCIsImVtYWlsIjoiZmF0dGFoemFkZWhAZ21haWwuY29tIiwiZXhwIjoyNTY0MDM0MTgzLCJvcmlnX2lhdCI6MTcwMDAzNDE4M30.wbxqBnk8pbHuOgKXEcAUwTRh0Jan-NAc2VIJTojxa9w"
//            ,'Content-type: application/json'
//        ]);
//        $result = curl_exec($ch);
//        return $result;
//
//    }
}
