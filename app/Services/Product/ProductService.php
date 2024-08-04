<?php

namespace App\Services\Product;

use App\Repositories\Price\PriceRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use App\Repositories\ProductColor\ProductColorRepositoryInterface;
use App\Repositories\Stock\StockRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface         $productRepository,
        private ProductColorRepositoryInterface    $productColorRepository,
        private StockRepositoryInterface           $stockRepository,
        private PriceRepositoryInterface           $priceRepository,
        private ProductCategoryRepositoryInterface $productCategoryRepository,
    )
    {
    }

    public function findProductByUrl(string $url): mixed
    {
        $product = $this->productRepository->findByUrl($url);
        if (!$product) {
            throw new ModelNotFoundException();
        }
        $this->productRepository->incrementViewCount($product);
        return $product;
    }

    public function dataTable(): mixed
    {
        return $this->productRepository->dataTable();
    }


    public function findById($id): mixed
    {
        return $this->productRepository->findById($id);
    }

    public function storeProduct($name, $url, $description, $study, $status, $categoryId, $colors): mixed
    {
        $product = $this->productRepository->createProduct($name, $url, $description, $study, $status);
        $this->productCategoryRepository->createProductCategory($product->id, $categoryId);
        foreach ($colors as $item) {
            $productColor = $this->productColorRepository->createProductColor($item["name"], $item["code"], $product->id, $item["status"]);
            $this->priceRepository->createPrice($productColor->id, $item["price"], $item["discount"]);
            $this->stockRepository->createStock($productColor->id, $item["stock"]);
        }
        return true;
    }

    public function updateProduct($id, $name, $url, $description, $study, $status, $categoryId, $colors): mixed
    {
        $this->productRepository->updateProduct($id, $name, $url, $description, $study, $status);
        $this->productCategoryRepository->updateWithProductId($id, $categoryId);
        foreach ($colors as $item) {
            if (isset($item["id"])) {
                $this->productColorRepository->updateProductColor($item["id"], $item["name"], $item["code"], $item["status"]);
                $this->priceRepository->updatePrice($item["id"], $item["price"], $item["discount"]);
                $this->stockRepository->updateStock($item["id"], $item["stock"]);
            } else {
                $productColor = $this->productColorRepository->createProductColor($item["name"], $item["code"], $id, $item["status"]);
                $this->priceRepository->createPrice($productColor->id, $item["price"], $item["discount"]);
                $this->stockRepository->createStock($productColor->id, $item["stock"]);
            }
        }
        return true;
    }
}
