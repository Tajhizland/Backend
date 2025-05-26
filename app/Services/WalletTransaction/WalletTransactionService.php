<?php

namespace App\Services\WalletTransaction;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\WalletTransaction\WalletTransactionRepositoryInterface;

class WalletTransactionService implements WalletTransactionServiceInterface
{
    public function __construct
    (
        private WalletTransactionRepositoryInterface $walletTransactionRepositoryInterface,
        private UserRepositoryInterface              $userRepositoryInterface,
    )
    {
    }

    public function charge($userId, $amount)
    {
        $user=$this->userRepositoryInterface->findOrFail($userId);
        $walletTransaction=$this->walletTransactionRepositoryInterface->create([
            'user_id'=>$userId,
            'amount'=>$amount,
            'status'=>1,
        ]);
    }
}
