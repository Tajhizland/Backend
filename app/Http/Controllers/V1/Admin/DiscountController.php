<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Discount\DiscountSetItemDto;
use App\DTOs\Discount\DiscountSortDto;
use App\DTOs\Discount\DiscountStoreDto;
use App\DTOs\Discount\DiscountUpdateDto;
use App\DTOs\Discount\DiscountUpdateItemDto;
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
        private readonly DiscountServiceInterface $discountService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(DiscountResource::collection($this->discountService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new DiscountResource($this->discountService->find($id)));
    }

    public function store(StoreDiscountRequest $request)
    {
        $this->discountService->store(new DiscountStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.discount")]));
    }

    public function update($id, UpdateDiscountRequest $request)
    {
        $this->discountService->update(new DiscountUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.discount")]));
    }

    public function getItem($id)
    {
        return $this->dataResponseCollection(DiscountItemResource::collection($this->discountService->getItem($id)));
    }

    public function getTopDiscountItem($id)
    {
        return $this->dataResponseCollection(DiscountItemResource::collection($this->discountService->getTopItem($id)));
    }

    public function setItem(SetDiscountRequest $request)
    {
        $this->discountService->setItem(new DiscountSetItemDto(...$request->validated()));
        return $this->successResponse(__("action.change", ["attr" => __("attr.discount")]));
    }

    public function updateItem(UpdateItemRequest $request)
    {
        $this->discountService->updateItem(new DiscountUpdateItemDto(...$request->validated()));
        return $this->successResponse(__("action.change", ["attr" => __("attr.discount")]));
    }

    public function deleteItem($id)
    {
        $this->discountService->deleteItem($id);
        return $this->successResponse(__("action.remove", ["attr" => __("attr.discount")]));
    }

    public function sort(SortTopRequest $request)
    {
        $this->discountService->sort(new DiscountSortDto(...$request->validated()));
        return $this->successResponse(__("action.sort", ["attr" => __("attr.discount")]));
    }
}
