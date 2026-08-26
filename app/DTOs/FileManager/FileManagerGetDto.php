<?php

namespace App\DTOs\FileManager;

class FileManagerGetDto
{
    public function __construct(
        public mixed $model_id,
        public mixed $model_type,
    )
    {
    }
}
