<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService implements ProductServiceInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
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

    public function getPaginatedFilterable(): mixed
    {
        return $this->productRepository->getPaginated();
    }


    public function findById($id): mixed
    {
        return $this->productRepository->findById($id);
    }

    public function storeProduct($name, $url, $description, $study, $categoryId, $colors): mixed
    {
        return 1;
        // TODO: Implement storeProduct() method.
    }

    public function updateProduct($id, $name, $url, $description, $study, $categoryId, $colors): mixed
    {
        return 1;
        // TODO: Implement storeProduct() method.
    }
}
