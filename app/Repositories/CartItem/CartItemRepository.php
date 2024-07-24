<?php

namespace App\Repositories\CartItem;

use App\Models\CartItem;
use App\Repositories\Base\BaseRepository;

class CartItemRepository extends  BaseRepository implements  CartItemRepositoryInterface
{
    public function __construct(CartItem $model)
    {
        parent::__construct($model);
    }

    public function getItemsByCartId($cartId)
    {
        return $this->model->where('cart_id', $cartId)->get();
    }

    public function findItem($cartId, $productId)
    {
        return $this->model->where('cart_id', $cartId)
            ->where('product_color_id', $productId)
            ->first();
    }

    public function addItem($cartId, $productId, $quantity)
    {
        return $this->model->create([
            'cart_id' => $cartId,
            'product_color_id' => $productId,
            'count' => $quantity,
        ]);
    }

    public function updateItem($cartId, $productId, $quantity)
    {
        $cartItem = $this->findItem($cartId, $productId);
        if ($cartItem) {
            $cartItem->update(['count' => $quantity]);
            return $cartItem;
        }
        return null;
    }

    public function removeItem($cartId, $productId)
    {
        $cartItem = $this->findItem($cartId, $productId);
        if ($cartItem) {
            return $cartItem->delete();
        }
        return false;
    }

    public function clearItems($cartId)
    {
        return $this->model->where('cart_id', $cartId)->delete();
    }

    public function increment($cartItem)
    {
       return $cartItem->increment('count');
    }
    public function decrement($cartItem)
    {
       return $cartItem->decrement('count');
    }

}
