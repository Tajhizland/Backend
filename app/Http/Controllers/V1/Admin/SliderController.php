<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\StoreSliderRequest;
use App\Http\Requests\V1\Admin\UpdateSliderRequest;
use App\Http\Resources\V1\Slider\SliderCollection;
use App\Http\Resources\V1\Slider\SliderResource;
use App\Services\Slider\SliderServiceInterface;
use Illuminate\Support\Facades\Lang;

class SliderController extends Controller
{
    public function __construct
    (
     private   SliderServiceInterface $sliderService
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new SliderCollection($this->sliderService->dataTable())) ;
    }
    public function findById($id)
    {
        return $this->dataResponse(new SliderResource($this->sliderService->findById($id))) ;
    }
    public function store(StoreSliderRequest $request)
    {
        $this->sliderService->store($request->get("title") , $request->get("url") ,$request->get("status"),$request->get("image"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.filter")]));
    }
    public function update(UpdateSliderRequest $request)
    {
        $this->sliderService->update($request->get("id"),$request->get("title") , $request->get("url") ,$request->get("status"),$request->get("image"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.filter")]));
    }
}
