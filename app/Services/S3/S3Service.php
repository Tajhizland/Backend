<?php

namespace App\Services\S3;

use Illuminate\Support\Facades\Storage;

class S3Service implements S3ServiceInterface
{
    public function upload($file, $path, $fileName = ""): string
    {
        if ($fileName = "")
            $fileName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = $path . '/' . $fileName;
        Storage::disk('s3')->put($filePath, file_get_contents($file));
        return $fileName;
    }

    public function remove($path): void
    {
        Storage::disk('s3')->delete($path);
    }
}
