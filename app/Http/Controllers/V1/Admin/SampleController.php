<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sample\SampleImageRequest;
use App\Http\Requests\Admin\Sample\SampleVideoRequest;
use App\Http\Requests\Admin\Sample\SortImageRequest;
use App\Http\Requests\Admin\Sample\SortVideoRequest;
use App\Http\Requests\Admin\SampleRequest;
use App\Http\Resources\Sample\SampleResource;
use App\Http\Resources\SampleVideo\SampleVideoResource;
use App\Services\Sample\SampleServiceInterface;
use App\Http\Resources\SampleImage\SampleImageResource;

class SampleController extends Controller
{
    public function __construct
    (
        private readonly SampleServiceInterface $sampleService
    )
    {
    }

    public function find()
    {
        return $this->dataResponse(new SampleResource($this->sampleService->find()));
    }

    public function update(SampleRequest $request)
    {
        $this->sampleService->update($request->get("content"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.sample")]));

    }

    public function uploadImage(SampleImageRequest $request)
    {
        $this->sampleService->uploadImage($request->file("image"));
        return $this->successResponse(__("action.upload", ["attr" => __("attr.image")]));

    }

    public function removeImage($id)
    {
        $this->sampleService->removeImage($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.image")]));
    }

    public function addVideo(SampleVideoRequest $request)
    {
        $this->sampleService->addVideo($request->get("vlog_id"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.sample")]));
    }

    public function deleteVideo($id)
    {
        $this->sampleService->deleteVideo($id);
        return $this->successResponse(__("action.update", ["attr" => __("attr.sample")]));
    }

    public function getImages()
    {
        return $this->dataResponseCollection(SampleImageResource::collection($this->sampleService->getImages()));
    }

    public function getVideos()
    {
        return $this->dataResponseCollection(SampleVideoResource::collection($this->sampleService->getVideos()));
    }
    public function sortVideo(SortVideoRequest $request)
    {
        $this->sampleService->sortVideo($request->get("video"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.vlog")]));
    }
    public function sortImage(SortImageRequest $request)
    {
        $this->sampleService->sortImage($request->get("image"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.image")]));
    }
}
