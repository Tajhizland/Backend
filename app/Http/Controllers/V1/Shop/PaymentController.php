<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Payment\PaymentRequest;
use App\Services\Payment\PaymentServicesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function __construct
    (
        private PaymentServicesInterface $paymentServices
    )
    {
    }

    public function requestPayment(PaymentRequest $request)
    {
        $paymentPath = $this->paymentServices->request(Auth::user()->id, $request->get("wallet"), $request->get("shippingMethod", 1) , $request->get("code") , $request->get("shippingPrice" , 0));
        return $this->dataResponse($paymentPath);
    }

    public function verifyPayment(Request $request)
    {
        try {
            $this->paymentServices->verifyPayment($request);
            return Redirect::to("https://tajhizland.com/thank_you_page");
        } catch (\Throwable $exception) {
            return Redirect::to("https://tajhizland.com/failed_payment");
        }
    }
}
