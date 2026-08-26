<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Transaction\TransactionService;
use App\Http\Resources\Transaction\TransactionResource;

class TransactionController extends Controller
{
    public function __construct
    (
        private TransactionService $transactionService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(TransactionResource::collection($this->transactionService->dataTable()));
    }
}
