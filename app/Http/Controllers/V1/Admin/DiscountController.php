<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Discount\SetDiscountRequest;
use App\Http\Requests\Admin\Discount\SortTopRequest;
use App\Http\Requests\Admin\Discount\StoreDiscountRequest;
use App\Http\Requests\Admin\Discount\UpdateDiscountRequest;
use App\Http\Requests\Admin\Discount\UpdateItemRequest;
use App\Http\Resources\Discount\DiscountResource;
use App\Http\Resources\DiscountItem\DiscountItemResource;
use App\Services\Discount\DiscountServiceInterface;

class DiscountController extends Controller
{
    public function __construct(
        private readonly DiscountServiceInterface $discountService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->discountService->dataTable();
        return $this->dataResponseCollection(DiscountResource::collection($response));
    }

    public function store(StoreDiscountRequest $request)
    {
        $this->discountService->store($request->get("title"), $request->get("status"), $request->get("start_date"), $request->get("end_date"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.discount")]));
    }

    public function update(UpdateDiscountRequest $request)
    {
        $this->discountService->update($request->get("id"), $request->get("title"), $request->get("status"), $request->get("start_date"), $request->get("end_date"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.discount")]));
    }

    public function find($id)
    {
        $response = $this->discountService->find($id);
        return $this->dataResponse(new DiscountResource($response));
    }

    public function getItem($id)
    {
        $response = $this->discountService->getItem($id);
        return $this->dataResponseCollection(DiscountItemResource::collection($response));
    }

    public function setItem(SetDiscountRequest $request)
    {
        $this->discountService->setItem($request->get("discount_id"), $request->get("discount"));
        return $this->successResponse(__("action.change", ["attr" => __("attr.discount")]));
    }

    public function updateItem(UpdateItemRequest $request)
    {
        $this->discountService->updateItem($request->get("discount"));
        return $this->successResponse(__("action.change", ["attr" => __("attr.discount")]));
    }

    public function deleteItem($id)
    {
        $this->discountService->deleteItem($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.discount")]));
    }

    public function getTopDiscountItem($id)
    {
        $response = $this->discountService->getTopItem($id);
        return $this->dataResponseCollection(DiscountItemResource::collection($response));
    }

    public function sort(SortTopRequest $request)
    {
        $this->discountService->sort($request->get("discounts"));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.discount")]));
    }
}
