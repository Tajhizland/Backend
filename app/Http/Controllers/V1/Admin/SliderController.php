<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Slider\SliderSortDto;
use App\DTOs\Slider\SliderStoreDto;
use App\DTOs\Slider\SliderUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\slider\StoreSliderRequest;
use App\Http\Requests\Admin\slider\UpdateSliderRequest;
use App\Http\Requests\Admin\SliderSortRequest;
use App\Http\Resources\Slider\SliderResource;
use App\Services\Slider\SliderServiceInterface;

class SliderController extends Controller
{
    public function __construct(
        private readonly SliderServiceInterface $sliderService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(SliderResource::collection($this->sliderService->dataTable()));
    }

    public function getAllDesktop()
    {
        return $this->dataResponseCollection(SliderResource::collection($this->sliderService->getAllDesktop()));
    }

    public function getAllMobile()
    {
        return $this->dataResponseCollection(SliderResource::collection($this->sliderService->getAllMobile()));
    }

    public function show($id)
    {
        return $this->dataResponse(new SliderResource($this->sliderService->find($id)));
    }

    public function store(StoreSliderRequest $request)
    {
        $this->sliderService->store(new SliderStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.slider")]));
    }

    public function update($id, UpdateSliderRequest $request)
    {
        $this->sliderService->update(new SliderUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.slider")]));
    }

    public function destroy($id)
    {
        $this->sliderService->delete($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.slider")]));
    }

    public function sort(SliderSortRequest $request)
    {
        $this->sliderService->sort(new SliderSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.slider")]));
    }
}
