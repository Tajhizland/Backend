<?php

namespace App\DTOs\Upload;

class UploadInitiateDto
{
    public function __construct(
        public int     $userId,
        public string  $profile,
        public string  $fileName,
        public int     $size,
        public ?string $mime = null,
    )
    {
    }
}
