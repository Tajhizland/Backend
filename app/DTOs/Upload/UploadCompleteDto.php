<?php

namespace App\DTOs\Upload;

class UploadCompleteDto
{
    public function __construct(
        public int    $userId,
        public string $key,
        public array  $parts = [],
    )
    {
    }
}
