<?php

namespace App\Services\ImageResize;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageResizeService implements ImageResizeServiceInterface
{
    public function resize($image , $width , $height  )
    {
        $image = Image::read($image);
        $image->resize($width, $height);
        return $image->encode();
    }
}
