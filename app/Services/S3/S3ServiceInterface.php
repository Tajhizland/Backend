<?php

namespace App\Services\S3;

interface S3ServiceInterface
{
    public function upload($file, $path): string;
    public function remove($path): void;
}
