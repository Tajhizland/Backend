<?php

namespace App\DTOs\FileManager;

class FileManagerUploadDto
{
    public function __construct(
        public mixed  $file,
        public string $model_type,
        public int    $model_id,
    )
    {
    }
}
