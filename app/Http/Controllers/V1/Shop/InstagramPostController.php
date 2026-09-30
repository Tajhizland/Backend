<?php

namespace App\Http\Controllers\V1\Shop;

use App\DTOs\InstagramPost\InstagramPostFindDto;
use App\DTOs\InstagramPost\InstagramPostListingDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\InstagramPost\FindInstagramPostRequest;
use App\Http\Requests\Shop\InstagramPost\InstagramPostListingRequest;
use App\Http\Resources\InstagramPost\InstagramPostResource;
use App\Services\InstagramPost\InstagramPostServiceInterface;

class InstagramPostController extends Controller
{
    public function __construct
    (
        private readonly InstagramPostServiceInterface $instagramPostService
    )
    {
    }

    public function listing(InstagramPostListingRequest $request)
    {
        $dto = new InstagramPostListingDto(...$request->validated());
        $listing = InstagramPostResource::collection($this->instagramPostService->listing($dto->filter))->response()->getData();

        return $this->dataResponse([
            "listing" => $listing,
        ]);
    }

    public function find(FindInstagramPostRequest $request)
    {
        $post = $this->instagramPostService->findActive((new InstagramPostFindDto(...$request->validated()))->id);
        $this->instagramPostService->view($post);

        return $this->dataResponse([
            "post" => new InstagramPostResource($post),
        ]);
    }
}
