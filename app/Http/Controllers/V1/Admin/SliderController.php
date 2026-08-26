<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\slider\StoreSliderRequest;
use App\Http\Requests\Admin\slider\UpdateSliderRequest;
use App\Http\Requests\Admin\SliderSortRequest;
use App\Http\Resources\Slider\SliderResource;
use App\Services\Slider\SliderServiceInterface;

class SliderController extends Controller
{
    public function __construct
    (
        private readonly SliderServiceInterface $sliderService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(SliderResource::collection($this->sliderService->dataTable()));
    }

    public function findById($id)
    {
        return $this->dataResponse(new SliderResource($this->sliderService->findById($id)));
    }

    public function store(StoreSliderRequest $request)
    {
        $this->sliderService->store($request->get("title"), $request->get("url"), $request->get("status"), $request->get("type"), $request->get("image"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.filter")]));
    }

    public function update(UpdateSliderRequest $request)
    {
        $this->sliderService->update($request->get("id"), $request->get("title"), $request->get("url"), $request->get("status"), $request->get("type"), $request->get("image"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.filter")]));
    }

    public function getAllDesktop()
    {
        $response = $this->sliderService->getAllDesktop();
        return $this->dataResponseCollection(SliderResource::collection($response));
    }

    public function getAllMobile()
    {
        $response = $this->sliderService->getAllMobile();
        return $this->dataResponseCollection(SliderResource::collection($response));
    }

    public function sort(SliderSortRequest $request)
    {
        $this->sliderService->sort($request->get("slider"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.slider")]));
    }
    public function delete($id)
    {
        $this->sliderService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.slider")]));
    }
}
