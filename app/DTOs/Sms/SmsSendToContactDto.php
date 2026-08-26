<?php

namespace App\DTOs\Sms;

class SmsSendToContactDto
{
    public function __construct(
        public string $message,
        public mixed  $mobiles = null,
    )
    {
    }
}
