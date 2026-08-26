<?php

namespace App\DTOs\User;

class UserWalletUpdateDto
{
    public function __construct(
        public int   $user_id,
        public mixed $wallet,
    )
    {
    }
}
