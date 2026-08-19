<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Coupon\CheckCouponRequest;
use App\Http\Requests\V1\Shop\OnHoldOrder\OnHoldOrderCheckoutPaymentRequest;
use App\Http\Resources\V1\Coupon\CouponResource;
use App\Http\Resources\V1\Delivery\DeliveryCollection;
use App\Http\Resources\V1\OnHoldOrder\OnHoldOrderCheckoutResource;
use App\Http\Resources\V1\OnHoldOrder\OnHoldOrderCollection;
use App\Repositories\Address\AddressRepositoryInterface;
use App\Services\Checkout\ShippingMethodResolver;
use App\Services\Coupon\CouponServiceInterface;
use App\Services\OnHoldOrder\OnHoldOrderServiceInterface;
use App\Services\Payment\PaymentServicesInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class OnHoldOrderController extends Controller
{
    public function __construct(
        private OnHoldOrderServiceInterface $onHoldOrderService,
        private PaymentServicesInterface    $paymentServices,
        private AddressRepositoryInterface  $addressRepository,
        private ShippingMethodResolver      $shippingMethodResolver,
        private CouponServiceInterface      $couponService,
    )
    {
    }

    public function userHoldOnPaginate()
    {
        return $this->dataResponseCollection(
            new OnHoldOrderCollection($this->onHoldOrderService->userHoldOnPaginate(Auth::user()->id))
        );
    }

    public function payment($id)
    {
        $paymentPath = $this->paymentServices->onHoldOrderRequest($id , Auth::user()->id);
        return $this->dataResponse(
            [
                "path" => $paymentPath
            ]
        );
    }
    public function paymentByWallet($id)
    {
        $paymentPath = $this->paymentServices->onHoldOrderVerifyByWallet($id , Auth::user()->id);
        return $this->dataResponse(
            $paymentPath
        );
    }

    /**
     * داده‌ی صفحه‌ی چک‌اوتِ یک سفارش معلقِ تاییدشده (اقلام قفل‌شده با قیمت فریزشده).
     */
    public function checkout($id)
    {
        return $this->dataResponse(
            new OnHoldOrderCheckoutResource($this->onHoldOrderService->checkoutData($id, Auth::user()->id))
        );
    }

    /**
     * روش‌های ارسال و هزینه‌ی پست، محاسبه‌شده روی اقلام همین سفارش معلق.
     */
    public function shippingMethods($id)
    {
        $userId = Auth::user()->id;
        $items = $this->onHoldOrderService->checkoutItems($id, $userId);
        $address = $this->addressRepository->findActiveByUserId($userId);
        $totalItemsPrice = 0;
        foreach ($items as $item) {
            $totalItemsPrice += $item->final_price * $item->count;
        }

        return $this->dataResponseCollection(new DeliveryCollection(
            $this->shippingMethodResolver->resolve($items, $address, $totalItemsPrice)
        ));
    }

    /**
     * بررسی کد تخفیف روی مبلغ همین سفارش معلق (نه سبد خرید فعلی کاربر).
     */
    public function checkCoupon(CheckCouponRequest $request, $id)
    {
        $userId = Auth::user()->id;
        $items = $this->onHoldOrderService->checkoutItems($id, $userId);
        $totalItemsPrice = 0;
        foreach ($items as $item) {
            $totalItemsPrice += $item->final_price * $item->count;
        }

        return $this->dataResponse(
            new CouponResource($this->couponService->check($request->get("code"), $userId, $totalItemsPrice))
        );
    }

    /**
     * پرداخت نهایی سفارش معلق با آدرس، روش ارسال، کد تخفیف، کیف پول و درگاه انتخابی کاربر.
     */
    public function checkoutPayment(OnHoldOrderCheckoutPaymentRequest $request, $id)
    {
        $result = $this->paymentServices->onHoldOrderCheckoutPayment(
            $id,
            Auth::user()->id,
            $request->boolean("wallet"),
            $request->get("shippingMethod"),
            $request->get("code"),
            $request->get("gateway", 1),
        );
        return $this->dataResponse($result);
    }

    public function remove($id)
    {
        return $this->dataResponse(
            new OnHoldOrderCollection($this->onHoldOrderService->userHoldOnPaginate($id)),
            Lang::get("action.remove",["attr"=>Lang::get("attr.order_request")])
        );
    }
}
