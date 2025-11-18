<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Discount\StoreDiscountRequest;
use App\Http\Requests\V1\Admin\Discount\UpdateDiscountRequest;
use App\Http\Resources\V1\Discount\DiscountCollection;
use App\Services\Discount\DiscountServiceInterface;
use Illuminate\Support\Facades\Lang;

class DiscountController extends Controller
{
    public function __construct(
        private DiscountServiceInterface $discountService
    )
    {
    }

    public function get($id)
    {
        $response = $this->discountService->getByCampaignId($id);
        return $this->dataResponseCollection(new DiscountCollection($response));
    }

    public function store(StoreDiscountRequest $request)
    {
        $this->discountService->store($request->get("campaign_id"), $request->get("product_color_id"), $request->get("discount"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.discount")]));
    }

    public function update(UpdateDiscountRequest $request)
    {
        $this->discountService->update($request->get("id"), $request->get("discount"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.discount")]));
    }

    public function delete($id)
    {
        $this->discountService->delete($id);
        return $this->successResponse(Lang::get("action.remove", ["attr" => Lang::get("attr.discount")]));
    }
}
