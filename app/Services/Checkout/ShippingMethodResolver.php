<?php

namespace App\Services\Checkout;

use App\Services\Delivery\DeliveryServiceInterface;
use App\Services\Tapin\CheckPrice;
use App\Services\Tapin\TapinService;

/**
 * محاسبه‌ی روش‌های ارسال و هزینه‌ی پست برای مجموعه‌ای از اقلام.
 *
 * هم با اقلام سبد خرید کار می‌کند و هم با اقلام یک سفارش (مثل سفارش معلقِ تاییدشده)،
 * چون هر دو رابطه‌ی productColor->product و فیلد count را دارند.
 */
readonly class ShippingMethodResolver
{
    public function __construct
    (
        private DeliveryServiceInterface $deliveryService,
        private CheckPrice               $checkPrice,
        private TapinService             $tapinService,
    )
    {
    }

    /**
     * @param iterable $items اقلام سبد خرید یا اقلام سفارش
     * @param mixed $address آدرس فعال کاربر (برای استعلام نرخ پست)
     * @param int $totalItemsPrice مبلغ کل اقلام، برای ارزش بیمه‌ی مرسوله
     */
    public function resolve($items, $address, $totalItemsPrice)
    {
        $size = 10;
        $isPacket = false;
        $isPacketAllow = true;
        $weight = 0;
        $width = 0;
        $height = 0;
        $length = 0;
        $hasInvalidProduct = false;

        if ($totalItemsPrice < 50000) {
            $totalItemsPrice = 50000;
        }

        foreach ($items as $item) {
            $product = $item->productColor->product;
            if (empty($product->weight) || $product->weight <= 0 ||
                empty($product->width) || empty($product->height) || empty($product->length) ||
                $product->width <= 0 || $product->height <= 0 || $product->length <= 0) {
                $hasInvalidProduct = true;
            }
            $weight += $product->weight;
            $width += $product->width;
            $height += $product->height;
            $length += $product->length;
            if ($product->is_packet) {
                if ($isPacketAllow) {
                    $isPacket = true;
                    $isPacketAllow = false;
                }
            }
        }

        $boxs = $this->tapinService->getBox();
        $boxs = json_decode(json_encode($boxs));
        $boxs = $boxs->entries->list;
        foreach ($boxs as $box) {
            if ($isPacket) {
                if ($box->pk < 10)
                    continue;
            } else {
                if ($box->pk > 10)
                    continue;
            }
            if ($box->length < $length && $box->width < $width && $box->height < $height) {
                $size = $box->pk;
            }
        }
        if ($size == 10 && $isPacket) {
            foreach ($boxs as $box) {
                if ($box->length < $length && $box->width < $width && $box->height < $height) {
                    $size = $box->pk;
                }
            }
        }
        if ($weight < 50) {
            $weight = 50;
        }
        if ($weight > 30000) {
            $hasInvalidProduct = true;
            $weight = 30000;
        }

        $response = $this->deliveryService->getActives();
        $filterResponse = [];
        foreach ($response as $delivery) {
            if ($delivery->id == 1) {
                // بدون آدرس فعال یا با محصولِ فاقد ابعاد/وزن، نرخ پست قابل استعلام نیست
                if ($hasInvalidProduct == true || !$address) {
                    continue;
                }
                $priceCheck = $this->checkPrice->check($address->province_id, $address->city_id, $weight, $totalItemsPrice, $size);
                $priceCheck = json_decode(json_encode($priceCheck));
                $delivery->price = ceil($priceCheck->entries->total_price / 1000) * 100;
                $filterResponse[] = $delivery;
            } else {
                $filterResponse[] = $delivery;
            }
        }
        return $filterResponse;
    }
}
