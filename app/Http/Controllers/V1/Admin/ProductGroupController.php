<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductGroup\AddFieldRequest;
use App\Http\Requests\Admin\ProductGroup\AddProductRequest;
use App\Http\Requests\Admin\ProductGroup\SetFieldValueRequest;
use App\Http\Resources\Product\SimpleProduct\SimpleProductResource;
use App\Services\ProductGroup\ProductGroupServiceInterface;
use Illuminate\Support\Facades\Lang;
use App\Http\Resources\GroupProduct\GroupProductResource;
use App\Http\Resources\GroupField\GroupFieldResource;

class ProductGroupController extends Controller
{
    public function __construct
    (
        private ProductGroupServiceInterface $productGroupService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->productGroupService->dataTable();
        return $this->dataResponseCollection(SimpleProductResource::collection($response));
    }

    public function getField($id)
    {
        $response = $this->productGroupService->getFieldByGroupId($id);
        return $this->dataResponseCollection(GroupFieldResource::collection($response));
    }

    public function getProduct($id)
    {
        $response = $this->productGroupService->getProductByGroupId($id);
        return $this->dataResponseCollection(GroupProductResource::collection($response));
    }

    public function getFieldValue($id)
    {
        $values = $this->productGroupService->getProductByGroupIdWithValue($id);
        $fields = $this->productGroupService->getFieldByGroupId($id);
        return $this->dataResponse(
            [
                "value" => GroupProductResource::collection($values)->response()->getData(),
                "fields" => GroupFieldResource::collection($fields)->response()->getData()
            ]
        );
    }

    public function addProduct(AddProductRequest $request)
    {
        $this->productGroupService->addProductToGroup($request->get("productId"), $request->get("groupId"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.group")]));
    }

    public function addField(AddFieldRequest $request)
    {
        $this->productGroupService->addFieldToGroup($request->get("title"), $request->get("groupId"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.group")]));
    }

    public function set(SetFieldValueRequest $request)
    {
        $this->productGroupService->setFieldValue($request->get("groupProductId"), $request->get("fieldId"), $request->get("value"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.group")]));
    }

    public function removeProduct($id)
    {
        $this->productGroupService->removeProductFromGroup($id);
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.group")]));
    }

    public function removeField($id)
    {
        $this->productGroupService->deleteFieldFromGroup($id);
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.group")]));
    }
}
