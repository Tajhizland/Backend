<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\WalletTransaction\WalletTransactionServiceInterface;
use App\Http\Resources\WalletTransaction\WalletTransactionResource;

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
        return $this->dataResponseCollection(WalletTransactionResource::collection($response));
    }
}
