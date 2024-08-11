<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentServicesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct
    (
       private PaymentServicesInterface $paymentServices
    )
    {
    }

    public function requestPayment()
    {
        $this->paymentServices->request(Auth::user()->id);
    }
    public function verifyPayment(Request $request)
    {
        $this->paymentServices->verifyPayment($request);
    }
}
