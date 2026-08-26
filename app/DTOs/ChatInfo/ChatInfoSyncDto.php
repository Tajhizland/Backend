<?php

namespace App\DTOs\ChatInfo;

class ChatInfoSyncDto
{
    public function __construct(
        public int $userId,
        public string $token,
    )
    {
    }
}
