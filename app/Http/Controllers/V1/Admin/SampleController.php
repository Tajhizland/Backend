<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Sample\SampleImageRequest;
use App\Http\Requests\V1\Admin\Sample\SampleVideoRequest;
use App\Http\Requests\V1\Admin\Sample\SortImageRequest;
use App\Http\Requests\V1\Admin\Sample\SortVideoRequest;
use App\Http\Requests\V1\Admin\SampleRequest;
use App\Http\Resources\V1\Sample\SampleResource;
use App\Http\Resources\V1\SampleImage\SampleImageCollection;
use App\Http\Resources\V1\SampleVideo\SampleVideoCollection;
use App\Http\Resources\V1\SampleVideo\SampleVideoResource;
use App\Services\Sample\SampleServiceInterface;
use Illuminate\Support\Facades\Lang;

class SampleController extends Controller
{
    public function __construct
    (
        private SampleServiceInterface $sampleService
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
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.sample")]));

    }

    public function uploadImage(SampleImageRequest $request)
    {
        $this->sampleService->uploadImage($request->file("image"));
        return $this->successResponse(Lang::get("action.upload", ["attr" => Lang::get("attr.image")]));

    }

    public function removeImage($id)
    {
        $this->sampleService->removeImage($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.image")]));
    }

    public function addVideo(SampleVideoRequest $request)
    {
        $this->sampleService->addVideo($request->get("vlog_id"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.sample")]));
    }

    public function deleteVideo($id)
    {
        $this->sampleService->deleteVideo($id);
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.sample")]));
    }

    public function getImages()
    {
        return $this->dataResponseCollection(new SampleImageCollection($this->sampleService->getImages()));
    }

    public function getVideos()
    {
        return $this->dataResponseCollection(new SampleVideoCollection($this->sampleService->getVideos()));
    }
    public function sortVideo(SortVideoRequest $request)
    {
        $this->sampleService->sortVideo($request->get("video"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.vlog")]));
    }
    public function sortImage(SortImageRequest $request)
    {
        $this->sampleService->sortImage($request->get("image"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.image")]));
    }
}
