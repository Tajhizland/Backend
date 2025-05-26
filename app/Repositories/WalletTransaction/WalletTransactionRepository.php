<?php

namespace App\Repositories\WalletTransaction;

use App\Models\WalletTransaction;
use App\Repositories\Base\BaseRepository;
use App\Services\WalletTransaction\WalletTransactionServiceInterface;

class WalletTransactionRepository extends BaseRepository implements WalletTransactionServiceInterface
{
    public function __construct(WalletTransaction $model)
    {
        parent::__construct($model);
    }
}
