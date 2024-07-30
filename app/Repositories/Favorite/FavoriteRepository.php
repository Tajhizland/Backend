<?php

namespace App\Repositories\Favorite;

use App\Models\Favorite;
use App\Repositories\Base\BaseRepository;

class FavoriteRepository extends  BaseRepository implements FavoriteRepositoryInterface
{
    public function __construct(Favorite $model)
    {
        parent::__construct($model);
    }

    public function addProduct($productId, $userId)
    {
       return $this->create(["product_id"=>$productId,"user_id"=>$userId]);
    }

    public function removeProduct($productId, $userId)
    {
       return $this->model->where("user_id",$userId)->where("product_id",$productId)->delete();
    }
    public function findProduct($productId,$userId)
    {
        return $this->model->where("user_id",$userId)->where("product_id",$productId)->first();
    }

}
