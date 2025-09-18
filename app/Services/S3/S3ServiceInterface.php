<?php

namespace App\Services\S3;

interface S3ServiceInterface
{
    public function download(string $s3Path, string $localPath): void;
    public function upload($file, $path , $fileName=""): string;
    public function upload2($file, $path , $fileName=""): string;
    public function remove($path): void;
    public function removeFolder($folderPath): void;

}
