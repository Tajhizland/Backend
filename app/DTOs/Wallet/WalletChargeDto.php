<?php

namespace App\DTOs\Wallet;

class WalletChargeDto
{
    public function __construct(
        public int $userId,
        public mixed $amount,
    )
    {
    }
}
