<?php

namespace App\Services\RandomProductCategory;

use App\DTOs\RandomProductCategory\RandomProductCategoryAddDto;
use App\Repositories\RandomProductCategory\RandomProductCategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class RandomProductCategoryService implements RandomProductCategoryServiceInterface
{
    public function __construct(
        private RandomProductCategoryRepositoryInterface $randomProductCategoryRepository,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->randomProductCategoryRepository->dataTable();
    }

    public function add(RandomProductCategoryAddDto $dto): mixed
    {
        return $this->randomProductCategoryRepository->add($dto->category_id);
    }

    public function find(int $id): mixed
    {
        $item = $this->randomProductCategoryRepository->find($id);
        if (!$item) {
            throw new NotFoundHttpException();
        }
        return $item;
    }

    public function delete(int $id): bool|null
    {
        return $this->randomProductCategoryRepository->delete($this->find($id));
    }
}
