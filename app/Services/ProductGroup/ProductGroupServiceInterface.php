<?php

namespace App\Services\ProductGroup;

use App\DTOs\ProductGroup\ProductGroupAddFieldDto;
use App\DTOs\ProductGroup\ProductGroupAddProductDto;
use App\DTOs\ProductGroup\ProductGroupSetFieldValueDto;

interface ProductGroupServiceInterface
{
    public function dataTable(): mixed;
    public function findByUrl($url);
    public function getFieldByGroupId($groupId);
    public function getFieldValueByGroupId($groupId);
    public function getProductByGroupId($groupId);
    public function getProductByGroupIdWithValue($groupId);

    public function addProductToGroup(ProductGroupAddProductDto $dto): mixed;

    public function removeProductFromGroup($id);

    public function addFieldToGroup(ProductGroupAddFieldDto $dto): mixed;

    public function deleteFieldFromGroup($fieldId);

    public function setFieldValue(ProductGroupSetFieldValueDto $dto): mixed;


}
