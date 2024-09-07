<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Transaction\TransactionCollection;
use App\Services\Transaction\TransactionService;

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
        return $this->dataResponseCollection(new TransactionCollection($this->transactionService->dataTable()));
    }
}
