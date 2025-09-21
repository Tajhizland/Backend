<?php

namespace App\Http\Controllers\V1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Poster\PosterResource;
use App\Http\Resources\V1\Sample\SampleResource;
use App\Http\Resources\V1\SampleImage\SampleImageCollection;
use App\Http\Resources\V1\SampleVideo\SampleVideoCollection;
use App\Repositories\Poster\PosterRepositoryInterface;
use App\Services\Sample\SampleServiceInterface;

class SampleController extends Controller
{
    public function __construct
    (
        private SampleServiceInterface    $sampleService,
        private PosterRepositoryInterface $posterRepository,

    )
    {
    }

    public function index()
    {
        $info = $this->sampleService->find();
        $image = $this->sampleService->getImages();
        $video = $this->sampleService->getVideos();
        $poster = $this->posterRepository->findOrFail(1);

        return $this->dataResponse([
            "info" => new SampleResource($info),
            "poster" => new PosterResource($poster),
            "image" => new SampleImageCollection($image),
            "video" => new SampleVideoCollection($video)
        ]);
    }
}
