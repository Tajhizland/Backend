<?php

namespace App\Repositories\Stock;

use App\Models\Stock;
use App\Repositories\Base\BaseRepository;

class StockRepository extends BaseRepository implements StockRepositoryInterface
{
    public function __construct(Stock $model)
    {
        parent::__construct($model);
    }

    public function createStock($productColorId, $stock)
    {
        $this->create(
            [
                "product_color_id" => $productColorId,
                "stock" => $stock
            ]
        );
    }

    public function updateStock($productColorId, $stock)
    {
        $this->model::where("product_color_id", $productColorId)
            ->update(["stock" => $stock]);
    }

    public function increment($productColorId, $count)
    {
        return $this->model::where("product_color_id", $productColorId)->increment('stock', $count);
    }

    public function decrement($productColorId, $count)
    {
        return $this->model::where("product_color_id", $productColorId)->decrement('stock', $count);
    }
}
