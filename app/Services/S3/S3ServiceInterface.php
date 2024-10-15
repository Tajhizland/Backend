<?php

namespace App\Services\S3;

interface S3ServiceInterface
{
    public function upload($file, $path , $fileName=""): string;
    public function upload2($file, $path , $fileName=""): string;
    public function remove($path): void;
}
