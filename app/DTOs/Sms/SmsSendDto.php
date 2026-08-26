<?php

namespace App\DTOs\Sms;

class SmsSendDto
{
    public function __construct(
        public string $message,
        public mixed  $userIds = null,
    )
    {
    }
}
