<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Landing\SetCategoryLandingRequest;
use App\Http\Requests\V1\Admin\Landing\SetLandingProductRequest;
use App\Http\Requests\V1\Admin\Landing\StoreLandingRequest;
use App\Http\Requests\V1\Admin\Landing\UpdateLandingRequest;
use App\Http\Resources\V1\Landing\LandingCollection;
use App\Http\Resources\V1\Landing\LandingResource;
use App\Http\Resources\V1\LandingCategory\LandingCategoryCollection;
use App\Http\Resources\V1\LandingProduct\LandingProductCollection;
use App\Services\Landing\LandingServiceInterface;
use Illuminate\Support\Facades\Lang;

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
        return $this->dataResponseCollection(new LandingCollection($this->landingService->dataTable()));
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
        return $this->dataResponseCollection(new LandingProductCollection($this->landingService->getProductByLanding($id)));
    }

    public function getCategory($id)
    {
        return $this->dataResponseCollection(new LandingCategoryCollection($this->landingService->getCategoryByLanding($id)));
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

    public function deleteProduct($id)
    {
        $this->landingService->deleteProduct($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.product") , "from" => Lang::get("attr.landing")]));
    }

    public function deleteCategory($id)
    {
        $this->landingService->deleteCategory($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.category") , "from" => Lang::get("attr.landing")]));
    }

}
