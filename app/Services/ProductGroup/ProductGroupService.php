<?php

namespace App\Services\ProductGroup;

use App\Repositories\GroupField\GroupFieldRepositoryInterface;
use App\Repositories\GroupFieldValue\GroupFieldValueRepositoryInterface;
use App\Repositories\GroupProduct\GroupProductRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductGroupService implements ProductGroupServiceInterface
{
    public function __construct
    (
        private ProductRepositoryInterface         $productRepository,
        private GroupProductRepositoryInterface    $groupProductRepository,
        private GroupFieldRepositoryInterface      $groupFieldRepository,
        private GroupFieldValueRepositoryInterface $groupFieldValueRepository,

    )
    {
    }

    public function dataTable()
    {
        return $this->productRepository->groupDataTable();
    }
    public function getFieldByGroupId($groupId)
    {
        return $this->groupFieldRepository->getByGroupId($groupId);
    }

    public function getFieldValueByGroupId($groupId)
    {
//        return $this->groupFieldValueRepository->getByGroupId($groupId);

    }
    public function getProductByGroupId($groupId)
    {
        return $this->groupProductRepository->getByGroupId($groupId);
    }
    public function getProductByGroupIdWithValue($groupId)
    {
        return $this->groupProductRepository->getByGroupIdWithValue($groupId);
    }
    public function addProductToGroup($productId, $groupId)
    {
        $groupProduct = $this->groupProductRepository->findByGroupAndProduct($productId, $groupId);
        if ($groupProduct) {
            throw new BadRequestHttpException("این محصول قبلا به این گروه اضافه شده است ");
        }
        return $this->groupProductRepository->create([
            "product_id" => $productId,
            "group_id" => $groupId
        ]);
    }

    public function removeProductFromGroup($id)
    {
        $groupProduct = $this->groupProductRepository->findOrFail($id);
        $this->groupFieldValueRepository->removeByGroupProduct($groupProduct->id);
        return $this->groupProductRepository->delete($groupProduct);
    }

    public function addFieldToGroup($title, $groupId)
    {
        return $this->groupFieldRepository->create(["title" => $title, "group_id" => $groupId]);
    }

    public function deleteFieldFromGroup($fieldId)
    {
        $groupField = $this->groupFieldRepository->findOrFail($fieldId);
        $this->groupFieldValueRepository->removeByFieldId($fieldId);
        return $this->groupFieldRepository->delete($groupField);
    }

    public function setFieldValue($groupProductId, $fieldId, $value)
    {
        $groupFieldValue = $this->groupFieldValueRepository->findByGroupAndField($groupProductId, $fieldId);
        if ($groupFieldValue) {
            return $this->groupFieldValueRepository->update($groupFieldValue,["value" => $value]);
        }
        return $this->groupFieldValueRepository->create([
            "group_field_id" => $fieldId,
            "group_product_id" => $groupProductId,
            "value" => $value,
        ]);

    }


}
