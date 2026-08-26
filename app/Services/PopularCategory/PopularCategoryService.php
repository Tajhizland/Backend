<?php

namespace App\Services\PopularCategory;

use App\DTOs\PopularCategory\PopularCategoryAddDto;
use App\Repositories\PopularCategory\PopularCategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class PopularCategoryService implements PopularCategoryServiceInterface
{
    public function __construct(private PopularCategoryRepositoryInterface $popularCategoryRepository)
    {
    }

    public function dataTable(): mixed
    {
       return $this->popularCategoryRepository->dataTable();
    }

    public function add(PopularCategoryAddDto $dto): mixed
    {
        return $this->popularCategoryRepository->add($dto->category_id);
    }

    public function find(int $id): mixed
    {
        $item = $this->popularCategoryRepository->find($id);
        if (!$item) {
            throw new NotFoundHttpException();
        }
        return $item;
    }

    public function delete(int $id): bool|null
    {
        return $this->popularCategoryRepository->delete($this->find($id));
    }
}
