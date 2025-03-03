<?php

namespace App\Services\ProductColor;

use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use App\Repositories\Stock\StockRepositoryInterface;

class ProductColorService implements ProductColorServiceInterface
{
    public function __construct
    (
        private ProductColorRepositoryInterface $productColorRepository,
        private PriceRepositoryInterface        $priceRepository,
        private StockRepositoryInterface        $stockRepository,
    )
    {

    }

    public function getByProductId($productId)
    {
        return $this->productColorRepository->getByProductId($productId);
    }

    public function setProductColor($productId, $colors)
    {
        foreach ($colors as $item) {
            if (isset($item["id"])) {
                $this->productColorRepository->updateProductColor($item["id"], $item["name"], $item["code"], $item["status"], $item["delivery_delay"]);
                $this->priceRepository->updatePrice($item["id"], $item["price"], $item["discount"]);
                $this->stockRepository->updateStock($item["id"], $item["stock"]);
            } else {
                $productColor = $this->productColorRepository->createProductColor($item["name"], $item["code"], $productId, $item["status"], $item["delivery_delay"]);
                $this->priceRepository->createPrice($productColor->id, $item["price"], $item["discount"]);
                $this->stockRepository->createStock($productColor->id, $item["stock"]);
            }
        }
    }

    public function colorFastUpdate($colors)
    {
        foreach ($colors as $item) {
            if (isset($item["id"])) {
                $productColor = $this->productColorRepository->findOrFail($item["id"]);
                $this->productColorRepository->update($productColor, ["status" => $item["status"], "delivery_delay" => $item["delivery_delay"]]);
                $this->priceRepository->updatePrice($item["id"], $item["price"], $item["discount"]);
                $this->stockRepository->updateStock($item["id"], $item["stock"]);
            }
        }
        return true;
    }
}
