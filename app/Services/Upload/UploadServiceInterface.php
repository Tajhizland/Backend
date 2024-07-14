<?php

namespace App\Services\Upload;

interface UploadServiceInterface
{
    public function upload($file, $path): string;
}
