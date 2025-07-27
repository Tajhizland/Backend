<?php

namespace App\Services\ProductGroup;

interface ProductGroupServiceInterface
{
    public function dataTable();
    public function findByUrl($url);
    public function getFieldByGroupId($groupId);
    public function getFieldValueByGroupId($groupId);
    public function getProductByGroupId($groupId);
    public function getProductByGroupIdWithValue($groupId);

    public function addProductToGroup($productId, $groupId);

    public function removeProductFromGroup($id);

    public function addFieldToGroup($title, $groupId);

    public function deleteFieldFromGroup($fieldId);

    public function setFieldValue($groupProductId, $fieldId, $value);


}
