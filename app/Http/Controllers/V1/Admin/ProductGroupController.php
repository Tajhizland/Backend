<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\ProductGroup\AddFieldRequest;
use App\Http\Requests\V1\Admin\ProductGroup\AddProductRequest;
use App\Http\Requests\V1\Admin\ProductGroup\SetFieldValueRequest;
use App\Http\Resources\V1\GroupField\GroupFieldCollection;
use App\Http\Resources\V1\GroupProduct\GroupProductCollection;
use App\Http\Resources\V1\Product\SimpleProduct\SimpleProductResource;
use App\Services\ProductGroup\ProductGroupServiceInterface;
use Illuminate\Support\Facades\Lang;

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
        return $this->dataResponseCollection(new GroupFieldCollection($response));
    }

    public function getProduct($id)
    {
        $response = $this->productGroupService->getProductByGroupId($id);
        return $this->dataResponseCollection(new GroupProductCollection($response));
    }

    public function getFieldValue($id)
    {
        $response = $this->productGroupService->getProductByGroupId($id);
        return $this->dataResponseCollection(new GroupProductCollection($response));
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
