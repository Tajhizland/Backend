<?php

namespace App\Repositories\Cart;

use App\Repositories\Base\BaseRepository;

class CartRepository extends BaseRepository implements  CartRepositoryInterface
{
    public function getCartByUserId($userId){
        return $this->model->where('user_id', $userId)->first();
    }
    public function createCart($userId)
    {
        return $this->model->create(['user_id' => $userId]);
    }
}
