<?php

namespace App\Repositories\ProductGuaranty;

use App\Models\ProductGuaranty;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Base\BaseRepositoryInterface;

class ProductGuarantyRepository extends BaseRepository implements ProductGuarantyRepositoryInterface
{
    public function __construct(ProductGuaranty $model)
    {
        parent::__construct($model);
    }

    public function deleteByProductId($productId)
    {
        return $this->model::where("product_id",$productId)->delete();
    }
}
