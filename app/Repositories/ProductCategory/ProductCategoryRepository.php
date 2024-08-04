<?php

namespace App\Repositories\ProductCategory;

use App\Models\ProductCategory;
use App\Repositories\Base\BaseRepository;

class ProductCategoryRepository extends BaseRepository implements ProductCategoryRepositoryInterface
{
    public function __construct(ProductCategory $model)
    {
        parent::__construct($model);
    }

    public function createProductCategory($productId, $categoryId)
    {
        return $this->create([
            "product_id" => $productId,
            "category_id" => $categoryId
        ]);
    }

    public function updateWithProductId($productId, $categoryId)
    {
        return $this->model::where("product_id", $productId)->update(["category_id" => $categoryId]);
    }
}
