<?php

namespace App\Repositories\Cart;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Repositories\Base\BaseRepository;

class CartRepository extends BaseRepository implements  CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }
    public function getCartByUserId($userId){
        return $this->model->where('user_id', $userId)->active()->first();
    }
    public function createCart($userId)
    {
        return $this->model->create(['user_id' => $userId , "status"=>CartStatus::Active->value]);
    }
    public function changeStatus(Cart $cart, $status)
    {
        return $cart->update(["status"=>$status]);
    }

    public function setDeliveryMethod(Cart $cart, $delivery_method)
    {
        $cart->update([
            "delivery_method" => $delivery_method
        ]);
    }

    public function setPaymentMethod(Cart $cart, $payment_method)
    {
        $cart->update([
            "payment_method" => $payment_method
        ]);
    }
}
