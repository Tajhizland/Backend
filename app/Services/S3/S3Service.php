<?php

namespace App\Services\S3;

use Illuminate\Support\Facades\Storage;

class S3Service implements S3ServiceInterface
{
    public function upload($file, $path, $fileName = ""): string
    {
        if ($fileName == "")
            $fileName = time() . "_" . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
        $filePath = $path . '/' . $fileName;
        Storage::disk('s3')->put($filePath,file_get_contents($file->getPathname()));
        return $fileName;
    }

    public function upload2($file, $path, $fileName = ""): string
    {
        if ($fileName == "")
            $fileName = time() . "_" . rand(10000, 99999) . '.jpg' ;
        $filePath = $path . '/' . $fileName;
        Storage::disk('s3')->put($filePath,$file);
//        Storage::disk('s3')->put($filePath, file_get_contents($file));
        return $fileName;
    }

    public function remove($path): void
    {
        Storage::disk('s3')->delete($path);
    }
    public function removeFolder($folderPath): void
    {
        $files = Storage::disk('s3')->allFiles($folderPath);
        Storage::disk('s3')->delete($files);
    }
}
