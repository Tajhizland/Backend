<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Tapin\TapinRegisterRequest;
use App\Services\Order\OrderServiceInterface;
use App\Services\Tapin\TapinService;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TapinController extends Controller
{
    public function __construct
    (
        private TapinService          $tapinService,
        private OrderServiceInterface $orderService
    )
    {
    }

    public function register($id, TapinRegisterRequest $request)
    {
        $order = $this->orderService->findById($id);
        $orderItems = $order->orderItems;
        $weight = 0;
        $width = 0;
        $height = 0;
        $length = 0;

        foreach ($orderItems as $orderItem) {
            $product = $orderItem->productColor->product;
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
        $tapin = $this->tapinService->send($order, $request->get("status"), $weight, $size);

        if (isset($tapin->returns->status) && $tapin->returns->status == 200) {
            throw new BadRequestHttpException("خطا از سمت سرویس پست :: " . json_encode($tapin, JSON_UNESCAPED_UNICODE));
        }
        if (!@ $tapin["entries"]["order_id"] || !@ $tapin["entries"]["barcode"]) {
            throw new BadRequestHttpException("خطا از سمت سرویس پست ! : " . json_encode($tapin, JSON_UNESCAPED_UNICODE));
        }
        $post_order_id = $tapin["entries"]["order_id"];
        $postReferenceID = $tapin["entries"]["barcode"];

        $this->orderService->setDeliveryToken($order->id, $postReferenceID);

        return $this->successResponse(Lang::get("action.send", ["attr" => Lang::get("attr.order")]));


    }
}
