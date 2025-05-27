<?php

namespace App\Repositories\WalletTransaction;

use App\Models\WalletTransaction;
use App\Repositories\Base\BaseRepository;

class WalletTransactionRepository extends BaseRepository implements WalletTransactionRepositoryInterface
{
    public function __construct(WalletTransaction $model)
    {
        parent::__construct($model);
    }
}
