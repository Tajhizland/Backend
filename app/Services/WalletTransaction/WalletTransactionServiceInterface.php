<?php

namespace App\Services\WalletTransaction;

use App\Repositories\Base\BaseRepositoryInterface;

interface WalletTransactionServiceInterface
{
    public function charge($userId, $amount);
}
