<?php

namespace App\Services\BlogCategory;

use App\DTOs\BlogCategory\BlogCategoryStoreDto;
use App\DTOs\BlogCategory\BlogCategoryUpdateDto;
use App\Repositories\BlogCategory\BlogCategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class BlogCategoryService implements BlogCategoryServiceInterface
{
    public function __construct(
        private BlogCategoryRepositoryInterface $blogCategoryRepository,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->blogCategoryRepository->dataTable();
    }

    public function list(): mixed
    {
        return $this->blogCategoryRepository->getActiveList();
    }

    public function find(int $id): mixed
    {
        $blogCategory = $this->blogCategoryRepository->find($id);
        if (!$blogCategory) {
            throw new NotFoundHttpException();
        }
        return $blogCategory;
    }

    public function store(BlogCategoryStoreDto $dto): mixed
    {
        return $this->blogCategoryRepository->create([
            "name" => $dto->name,
            "status" => $dto->status,
            "url" => $dto->url,
        ]);
    }

    public function update(BlogCategoryUpdateDto $dto): bool
    {
        $blogCategory = $this->find($dto->blogCategoryId);
        return $this->blogCategoryRepository->update($blogCategory, [
            "name" => $dto->name,
            "status" => $dto->status,
            "url" => $dto->url,
        ]);
    }
}
