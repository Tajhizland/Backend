<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\InstagramPost\InstagramPostMediaDto;
use App\DTOs\InstagramPost\InstagramPostSortDto;
use App\DTOs\InstagramPost\InstagramPostSortMediaDto;
use App\DTOs\InstagramPost\InstagramPostStoreDto;
use App\DTOs\InstagramPost\InstagramPostUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstagramPost\InstagramPostMediaRequest;
use App\Http\Requests\Admin\InstagramPost\InstagramPostCoverRequest;
use App\Http\Requests\Admin\InstagramPost\InstagramPostSortMediaRequest;
use App\Http\Requests\Admin\InstagramPost\InstagramPostSortRequest;
use App\Http\Requests\Admin\InstagramPost\StoreInstagramPostRequest;
use App\Http\Requests\Admin\InstagramPost\UpdateInstagramPostRequest;
use App\Http\Resources\InstagramPost\InstagramPostResource;
use App\Http\Resources\InstagramPostMedia\InstagramPostMediaResource;
use App\Services\InstagramPost\InstagramPostServiceInterface;
use Illuminate\Support\Facades\Auth;

class InstagramPostController extends Controller
{
    public function __construct
    (
        private readonly InstagramPostServiceInterface $instagramPostService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(InstagramPostResource::collection($this->instagramPostService->dataTable()));
    }

    public function list()
    {
        return $this->dataResponseCollection(InstagramPostResource::collection($this->instagramPostService->list()));
    }

    public function show($id)
    {
        return $this->dataResponse(new InstagramPostResource($this->instagramPostService->find($id)));
    }

    public function store(StoreInstagramPostRequest $request)
    {
        $post = $this->instagramPostService->store(new InstagramPostStoreDto(Auth::user()->id, ...$request->validated()));
        return $this->dataResponse(
            new InstagramPostResource($post),
            __("action.store", ["attr" => __("attr.instagram_post")])
        );
    }

    public function update($id, UpdateInstagramPostRequest $request)
    {
        $this->instagramPostService->update(new InstagramPostUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.instagram_post")]));
    }

    public function destroy($id)
    {
        $this->instagramPostService->destroy($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.instagram_post")]));
    }

    public function sort(InstagramPostSortRequest $request)
    {
        $this->instagramPostService->sort(new InstagramPostSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.instagram_post")]));
    }

    public function getMedia($id)
    {
        return $this->dataResponseCollection(InstagramPostMediaResource::collection($this->instagramPostService->getMedia($id)));
    }

    public function addMedia(InstagramPostMediaRequest $request)
    {
        $this->instagramPostService->addMedia(new InstagramPostMediaDto(...$request->validated()));
        return $this->successResponse(__("action.upload", ["attr" => __("attr.instagram_media")]));
    }

    public function removeMedia($id)
    {
        $this->instagramPostService->removeMedia($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.instagram_media")]));
    }

    public function updateCover($id, InstagramPostCoverRequest $request)
    {
        $media = $this->instagramPostService->updateCover((int) $id, $request->validated('cover'));
        return $this->dataResponse(new InstagramPostMediaResource($media), 'کاور ویدیو ذخیره شد.');
    }

    public function sortMedia(InstagramPostSortMediaRequest $request)
    {
        $this->instagramPostService->sortMedia(new InstagramPostSortMediaDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.instagram_media")]));
    }
}
