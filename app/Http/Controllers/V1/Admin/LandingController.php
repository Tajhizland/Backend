<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Landing\SetBannerRequest;
use App\Http\Requests\Admin\Landing\SetCategoryLandingRequest;
use App\Http\Requests\Admin\Landing\SetLandingProductRequest;
use App\Http\Requests\Admin\Landing\StoreLandingRequest;
use App\Http\Requests\Admin\Landing\UpdateLandingRequest;
use App\Http\Resources\Landing\LandingResource;
use App\Services\Landing\LandingServiceInterface;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\LandingBanner\LandingBannerResource;
use App\Http\Resources\LandingCategory\LandingCategoryResource;
use App\Http\Resources\LandingProduct\LandingProductResource;

class LandingController extends Controller
{
    public function __construct
    (
        private LandingServiceInterface $landingService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(LandingResource::collection($this->landingService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new LandingResource($this->landingService->findById($id)));
    }

    public function store(StoreLandingRequest $request)
    {
        $this->landingService->store($request->get("title"),$request->get("description"),$request->get("status"),$request->get("url"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.landing")]));
    }

    public function update(UpdateLandingRequest $request)
    {
        $this->landingService->update($request->get("id"),$request->get("title"),$request->get("description"),$request->get("status"),$request->get("url"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.landing")]));
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
        $this->landingService->setProduct($request->get("landing_id") ,$request->get("product_id"));
        return $this->successResponse(Lang::get("action.add_to", ["attr" => Lang::get("attr.product") , "to" => Lang::get("attr.landing")]));
    }

    public function setCategory(SetCategoryLandingRequest $request)
    {
        $this->landingService->setCategory($request->get("landing_id") ,$request->get("category_id"));
        return $this->successResponse(Lang::get("action.add_to", ["attr" => Lang::get("attr.category") , "to" => Lang::get("attr.landing")]));
    }

    public function setBanner(SetBannerRequest $request)
    {
        $this->landingService->setBanner($request->file("image"),$request->get("url") ,$request->get("landing_id"),$request->get("slider"));
        return $this->successResponse(Lang::get("action.add_to", ["attr" => Lang::get("attr.banner") , "to" => Lang::get("attr.landing")]));
    }

    public function deleteProduct($id)
    {
        $this->landingService->deleteProduct($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.product") , "from" => Lang::get("attr.landing")]));
    }
    public function deleteBanner($id)
    {
        $this->landingService->deleteBanner($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.banner") , "from" => Lang::get("attr.landing")]));
    }

    public function deleteCategory($id)
    {
        $this->landingService->deleteCategory($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.category") , "from" => Lang::get("attr.landing")]));
    }

}
