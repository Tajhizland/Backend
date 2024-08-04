<?php

namespace App\Repositories\Stock;

use App\Repositories\Base\BaseRepositoryInterface;

interface StockRepositoryInterface extends BaseRepositoryInterface
{
    public function createStock($productColorId, $stock);

    public function updateStock($productColorId, $stock);

    public function increment($productColorId, $count);

    public function decrement($productColorId, $count);
}
