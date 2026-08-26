<?php

namespace App\DTOs\Upload;

class UploadAbortDto
{
    public function __construct(
        public int    $userId,
        public string $key,
    )
    {
    }
}
