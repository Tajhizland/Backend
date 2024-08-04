<?php

namespace App\Services\Upload;

interface S3ServiceInterface
{
    public function upload($file, $path): string;
    public function remove($path): void;
}
