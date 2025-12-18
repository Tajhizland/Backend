<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Discount\SetDiscountRequest;
use App\Http\Requests\V1\Admin\Discount\SortTopRequest;
use App\Http\Requests\V1\Admin\Discount\StoreDiscountRequest;
use App\Http\Requests\V1\Admin\Discount\UpdateDiscountRequest;
use App\Http\Requests\V1\Admin\Discount\UpdateItemRequest;
use App\Http\Resources\V1\Discount\DiscountCollection;
use App\Http\Resources\V1\Discount\DiscountResource;
use App\Http\Resources\V1\DiscountItem\DiscountItemCollection;
use App\Http\Resources\V1\DiscountItem\DiscountItemResource;
use App\Services\Discount\DiscountServiceInterface;
use Illuminate\Support\Facades\Lang;

class DiscountController extends Controller
{
    public function __construct(
        private DiscountServiceInterface $discountService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->discountService->dataTable();
        return $this->dataResponseCollection(new DiscountCollection($response));
    }

    public function store(StoreDiscountRequest $request)
    {
        $this->discountService->store($request->get("title"), $request->get("status"), $request->get("start_date"), $request->get("end_date"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.discount")]));
    }

    public function update(UpdateDiscountRequest $request)
    {
        $this->discountService->update($request->get("id"), $request->get("title"), $request->get("status"), $request->get("start_date"), $request->get("end_date"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.discount")]));
    }

    public function find($id)
    {
        $response = $this->discountService->find($id);
        return $this->dataResponse(new DiscountResource($response));
    }

    public function getItem($id)
    {
        $response = $this->discountService->getItem($id);
        return $this->dataResponseCollection(new DiscountItemCollection($response));
    }

    public function setItem(SetDiscountRequest $request)
    {
        $this->discountService->setItem($request->get("discount_id"), $request->get("discount"));
        return $this->successResponse(Lang::get("action.change", ["attr" => Lang::get("attr.discount")]));
    }

    public function updateItem(UpdateItemRequest $request)
    {
        $this->discountService->updateItem($request->get("discount"));
        return $this->successResponse(Lang::get("action.change", ["attr" => Lang::get("attr.discount")]));
    }

    public function deleteItem($id)
    {
        $this->discountService->deleteItem($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.discount")]));
    }

    public function getTopDiscountItem($id)
    {
        $response = $this->discountService->getTopItem($id);
        return $this->dataResponseCollection(new DiscountItemCollection($response));
    }

    public function sort(SortTopRequest $request)
    {
        $this->discountService->sort($request->get("discount"));
        return $this->successResponse(Lang::get("action.sort", ["attr" => Lang::get("attr.discount")]));
    }
}
