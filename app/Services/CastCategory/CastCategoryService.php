<?php

namespace App\Services\CastCategory;

use App\DTOs\CastCategory\CastCategoryStoreDto;
use App\DTOs\CastCategory\CastCategoryUpdateDto;
use App\Repositories\CastCategory\CastCategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class CastCategoryService implements CastCategoryServiceInterface
{

    public function __construct
    (
        private CastCategoryRepositoryInterface $castCategoryRepository,
        private S3ServiceInterface              $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->castCategoryRepository->dataTable();
    }

    public function get(): mixed
    {
        return $this->castCategoryRepository->getActives();
    }

    public function find(int $id): mixed
    {
        $castCategory = $this->castCategoryRepository->find($id);
        if (!$castCategory) {
            throw new NotFoundHttpException();
        }
        return $castCategory;
    }

    public function store(CastCategoryStoreDto $dto): mixed
    {
        return $this->castCategoryRepository->create([
            'name' => $dto->name,
            'status' => $dto->status,
            'icon' => $this->s3Service->upload($dto->icon, "cast-category"),
        ]);
    }

    public function update(CastCategoryUpdateDto $dto): bool
    {
        $castCategory = $this->find($dto->castCategoryId);
        $iconPath = $castCategory->icon;
        if ($dto->icon) {
            $this->s3Service->remove("cast-category/" . $iconPath);
            $iconPath = $this->s3Service->upload($dto->icon, "cast-category");
        }
        return $this->castCategoryRepository->update($castCategory, [
            'name' => $dto->name,
            'status' => $dto->status,
            'icon' => $iconPath,
        ]);
    }

}
