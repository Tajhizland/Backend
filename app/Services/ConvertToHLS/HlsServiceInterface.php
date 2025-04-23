<?php

namespace App\Services\ConvertToHLS;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface HlsServiceInterface
{
    public function convertAndUploadS3(UploadedFile $file);
}
