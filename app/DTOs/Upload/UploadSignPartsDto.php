<?php

namespace App\DTOs\Upload;

class UploadSignPartsDto
{
    public function __construct(
        public int    $userId,
        public string $key,
        public array  $partNumbers,
    )
    {
    }
}
