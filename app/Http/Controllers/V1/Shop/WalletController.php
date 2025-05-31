<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Wallet\ChargeWalletRequest;
use App\Services\Payment\PaymentServicesInterface;
use App\Services\WalletTransaction\WalletTransactionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;

class WalletController extends Controller
{
    public function __construct
    (
        private WalletTransactionServiceInterface $walletTransactionService,
        private PaymentServicesInterface          $paymentServices,
    )
    {
    }

    public function chargeWallet(ChargeWalletRequest $request)
    {
        $path = $this->walletTransactionService->chargeRequest(Auth::user()->id, $request->get("amount"));
        return $this->dataResponse(["path" => $path]);
    }

    public function verifyWallet(Request $request)
    {
        try {
            $this->walletTransactionService->verifyCharge($request);
            return Redirect::to("https://tajhizland.com/thank_you_page");
        } catch (\Throwable $exception) {
            return Redirect::to("https://tajhizland.com/failed_payment");
        }
    }

    public function paymentOrderByWallet()
    {
        $response = $this->paymentServices->verifyOrderByWallet(Auth::user()->id);
        return $this->dataResponse($response);

    }
}
