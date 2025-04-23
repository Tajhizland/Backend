<?php

namespace App\Repositories\ProductVideo;

use App\Models\ProductVideo;
use App\Repositories\Base\BaseRepository;

class ProductVideoRepository extends BaseRepository implements ProductVideoRepositoryInterface
{
    public function __construct(ProductVideo $model)
    {
        parent::__construct($model);
    }

    public function getByProductId($productId)
    {
        return $this->model::where("product_id",$productId)->with("vlog")->get();
    }
}
