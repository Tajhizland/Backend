<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Shop\Wallet\ChargeWalletRequest;
use App\Services\WalletTransaction\WalletTransactionServiceInterface;

class WalletController extends Controller
{
    public function __construct
    (
        private WalletTransactionServiceInterface $walletTransactionService
    )
    {
    }

    public function chargeWallet(ChargeWalletRequest $request)
    {

    }
}
