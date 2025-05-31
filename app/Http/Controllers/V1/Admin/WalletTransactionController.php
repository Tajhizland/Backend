<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\WalletTransaction\WalletTransactionCollection;
use App\Services\WalletTransaction\WalletTransactionServiceInterface;

class WalletTransactionController extends Controller
{
    public function __construct
    (
        private WalletTransactionServiceInterface $walletTransactionService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->walletTransactionService->dataTable();
        return $this->dataResponseCollection(new WalletTransactionCollection($response));
    }
}
