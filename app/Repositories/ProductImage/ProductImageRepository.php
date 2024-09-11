<?php

namespace App\Repositories\ProductImage;

use App\Models\ProductImage;
use App\Repositories\Base\BaseRepository;

class ProductImageRepository extends BaseRepository implements ProductImageRepositoryInterface
{
    public function __construct(ProductImage $model)
    {
        parent::__construct($model);
    }
    public function getByProductId($productId)
    {
        return $this->model::where("product_id",$productId)->get();
    }
}
