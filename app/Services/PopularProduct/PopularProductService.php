<?php

namespace App\Services\PopularProduct;

use App\DTOs\PopularProduct\PopularProductAddDto;
use App\Repositories\PopularProduct\PopularProductRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class PopularProductService implements  PopularProductServiceInterface
{
    public function __construct(private PopularProductRepositoryInterface $popularProductRepository)
    {
    }

    public function dataTable(): mixed
    {
       return $this->popularProductRepository->dataTable();
    }

    public function add(PopularProductAddDto $dto): mixed
    {
        return $this->popularProductRepository->add($dto->product_id);
    }

    public function find(int $id): mixed
    {
        $item = $this->popularProductRepository->find($id);
        if (!$item) {
            throw new NotFoundHttpException();
        }
        return $item;
    }
    public function get(): mixed
    {
        return  $this->popularProductRepository->getWithProduct();
    }

    public function delete(int $id): bool|null
    {
        return $this->popularProductRepository->delete($this->find($id));
    }
}
