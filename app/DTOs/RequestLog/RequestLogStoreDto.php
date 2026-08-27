<?php

namespace App\DTOs\RequestLog;

class RequestLogStoreDto
{
    public function __construct(
        public ?string $title = null,
        public mixed   $request = null,
        public mixed   $response = null,
    )
    {
    }
}
