<?php

namespace App\Services\ImageResize;

interface ImageResizeServiceInterface
{
    public function resize($image , $width , $height );
}
