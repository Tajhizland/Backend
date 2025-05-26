<?php

namespace App\Services\WalletTransaction;

use App\Repositories\Base\BaseRepositoryInterface;

interface WalletTransactionServiceInterface
{
    public function chargeRequest($userId, $amount);
    public function verifyCharge($request);

}
