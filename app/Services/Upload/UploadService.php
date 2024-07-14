<?php

namespace App\Services\Upload;

use Illuminate\Support\Facades\Storage;

class UploadService implements  UploadServiceInterface
{
    public function upload($file, $path): string
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = $path . '/' . $fileName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        return Storage::disk('s3')->url($filePath);
    }
}
