<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Landing\LandingSetBannerDto;
use App\DTOs\Landing\LandingSetCategoryDto;
use App\DTOs\Landing\LandingSetProductDto;
use App\DTOs\Landing\LandingStoreDto;
use App\DTOs\Landing\LandingUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Landing\SetBannerRequest;
use App\Http\Requests\Admin\Landing\SetCategoryLandingRequest;
use App\Http\Requests\Admin\Landing\SetLandingProductRequest;
use App\Http\Requests\Admin\Landing\StoreLandingRequest;
use App\Http\Requests\Admin\Landing\UpdateLandingRequest;
use App\Http\Resources\Landing\LandingResource;
use App\Services\Landing\LandingServiceInterface;
use App\Http\Resources\LandingBanner\LandingBannerResource;
use App\Http\Resources\LandingCategory\LandingCategoryResource;
use App\Http\Resources\LandingProduct\LandingProductResource;

class LandingController extends Controller
{
    public function __construct
    (
        private readonly LandingServiceInterface $landingService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(LandingResource::collection($this->landingService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new LandingResource($this->landingService->find($id)));
    }

    public function store(StoreLandingRequest $request)
    {
        $this->landingService->store(new LandingStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.landing")]));
    }

    public function update($id, UpdateLandingRequest $request)
    {
        $this->landingService->update(new LandingUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.landing")]));
    }

    public function getProduct($id)
    {
        return $this->dataResponseCollection(LandingProductResource::collection($this->landingService->getProductByLanding($id)));
    }

    public function getCategory($id)
    {
        return $this->dataResponseCollection(LandingCategoryResource::collection($this->landingService->getCategoryByLanding($id)));
    }
    public function getBanner($id)
    {
        return $this->dataResponseCollection(LandingBannerResource::collection($this->landingService->getBanner($id)));
    }
    public function setProduct(SetLandingProductRequest $request)
    {
        $this->landingService->setProduct(new LandingSetProductDto(...$request->validated()));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.product") , "to" => __("attr.landing")]));
    }

    public function setCategory(SetCategoryLandingRequest $request)
    {
        $this->landingService->setCategory(new LandingSetCategoryDto(...$request->validated()));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.category") , "to" => __("attr.landing")]));
    }

    public function setBanner(SetBannerRequest $request)
    {
        $this->landingService->setBanner(new LandingSetBannerDto(...$request->validated()));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.banner") , "to" => __("attr.landing")]));
    }

    public function deleteProduct($id)
    {
        $this->landingService->deleteProduct($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.product") , "from" => __("attr.landing")]));
    }
    public function deleteBanner($id)
    {
        $this->landingService->deleteBanner($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.banner") , "from" => __("attr.landing")]));
    }

    public function deleteCategory($id)
    {
        $this->landingService->deleteCategory($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.category") , "from" => __("attr.landing")]));
    }

}
