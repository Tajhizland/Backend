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

    public function findItem($cartId, $productId,$guarantyId)
    {
        return $this->model->where('cart_id', $cartId)
            ->where('product_color_id', $productId)
            ->where('guaranty_id', $guarantyId)
            ->first();
    }

    public function addItem($cartId, $productId, $quantity,$guarantyId)
    {
        return $this->model->create([
            'cart_id' => $cartId,
            'product_color_id' => $productId,
            'guaranty_id' => $guarantyId,
            'count' => $quantity,
        ]);
    }

    public function updateItem($cartId, $productId, $quantity,$guarantyId)
    {
        $cartItem = $this->findItem($cartId, $productId,$guarantyId);
        if ($cartItem) {
            $cartItem->update(['count' => $quantity]);
            return $cartItem;
        }
        return null;
    }

    public function removeItem($cartId, $productId,$guarantyId)
    {
        $cartItem = $this->findItem($cartId, $productId,$guarantyId);
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
