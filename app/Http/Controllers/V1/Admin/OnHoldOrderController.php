<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OnHoldOrder\OnHoldOrderResource;
use App\Http\Resources\Order\OrderResource;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use Morilog\Jalali\Jalalian;

class OnHoldOrderController extends Controller
{
    public function __construct
    (
        private readonly OnHoldOrderServiceInterface $onHoldOrderService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(OnHoldOrderResource::collection($this->onHoldOrderService->dataTable())) ;
    }
    public function show($id)
    {
        $onHoldOrder = $this->onHoldOrderService->findById($id);
        $order = $this->onHoldOrderService->findOrderById($id);

        // وضعیت خودِ درخواستِ معلق کنار سفارش برمی‌گردد تا پنل ادمین بداند
        // این درخواست قبلا بررسی (تایید/رد) شده یا نه.
        return $this->dataResponse(array_merge(
            (new OrderResource($order))->resolve(request()),
            [
                'on_hold_id' => $onHoldOrder->id,
                'on_hold_status' => $onHoldOrder->status,
                'on_hold_review_date' => $onHoldOrder->review_date
                    ? Jalalian::fromDateTime($onHoldOrder->review_date)->format('Y/m/d H:i:s')
                    : null,
                'on_hold_expire_date' => $onHoldOrder->expire_date
                    ? Jalalian::fromDateTime($onHoldOrder->expire_date)->format('Y/m/d H:i:s')
                    : null,
            ]
        ));
    }
    public function accept($id)
    {
        $this->onHoldOrderService->setAccept($id);
        return $this->successResponse(__("action.accept",["attr"=>__("attr.order_request")]));

    }
    public function reject($id)
    {
        $this->onHoldOrderService->setReject($id);
        return $this->successResponse(__("action.reject",["attr"=>__("attr.order_request")]));
    }
}
