<?php

namespace App\Services\Tapin;

use App\Repositories\City\CityRepositoryInterface;
use App\Repositories\TapinCity\TapinCityRepositoryInterface;
use App\Service\Lang\LangService;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TapinService implements TapinServiceInterface
{
    public function __construct
    (
        private CityRepositoryInterface      $cityRepository,
        private TapinCityRepositoryInterface $tapinCityRepository
    )
    {
    }

    public function send($order, $postStatus, $weight,    $boxId=10)
    {
        try {
            $data = $this->getData($order, $boxId, $postStatus, $weight );
            $response = Http::withHeaders([
                'Authorization' =>config("tapin.token"),
                'Content-Type' => 'application/json',
            ])->post( 'https://api.tapin.ir/api/v2/public/order/post/register/', $data);

            if ($response->successful()) {
                return $response->json();
            }
            throw new BadRequestHttpException(json_encode($response));
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    private function getData($order, $boxId, $postStatus, $weight )
    {

        $orderInfo = $order->orderInfo;
        $items = [];
        $items["count"] = 1;
        $items["discount"] = 0;
        $items["title"] = "محصولات لوازم خانگی";
        $items["weight"] = round($weight);
        $items["price"] = $order->total_price * 10;
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
        $data["province_code"] = $orderInfo->province_id;
        $data["description"] = null;
        $data["email"] = null;
        $data["employee_code"] = "-1";
        $data["first_name"] = $orderInfo->name;
        $data["last_name"] = $orderInfo->last_name;
        $data["mobile"] = $orderInfo->mobile;
        $data["phone"] = null;
        $data["postal_code"] = $orderInfo->zip_code;
        $data["pay_type"] = 1;
        $data["order_type"] = 1;
        $data["package_weight"] = 0;
        $data["manual_id"] = $order->id ;
        $data["box_id"] = $boxId;
        $data["products"] = [$items];
        return $data;
    }

    public function checkPrice($province, $city, $weight, $price, $boxId = 10)
    {


        $data = [
            "shop_id" => config("tapin.shopId"),
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
            "box_id" => $boxId,
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
        return Http::withToken(config("tapin.token"), "")->post('https://api.tapin.ir/api/v2/public/order/post/check-price/', $data)->json();
    }

    public function getBox()
    {
        $data = [
            "shop_id" => config("tapin.shopId"),
        ];
        return Http::withToken(config("tapin.token"), "")->post('https://api.tapin.ir/api/v2/public/order/post/packing-box/', $data)->json();
    }

}
