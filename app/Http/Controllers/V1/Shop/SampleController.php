<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Sample\SampleResource;
use App\Http\Resources\V1\SampleImage\SampleImageCollection;
use App\Http\Resources\V1\SampleVideo\SampleVideoCollection;
use App\Services\Sample\SampleServiceInterface;

class SampleController extends Controller
{
    public function __construct
    (
        private SampleServiceInterface $sampleService
    )
    {
    }

    public function index()
    {
        $info = $this->sampleService->find();
        $image = $this->sampleService->getImages();
        $video = $this->sampleService->getVideos();

        return $this->dataResponse([
            "info" => new SampleResource($info),
            "images" => new SampleImageCollection($image),
            "video" => new SampleVideoCollection($video)
        ]);
    }
}
