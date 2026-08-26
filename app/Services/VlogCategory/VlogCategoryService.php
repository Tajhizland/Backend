<?php

namespace App\Services\VlogCategory;

use App\DTOs\VlogCategory\VlogCategorySortDto;
use App\DTOs\VlogCategory\VlogCategoryStoreDto;
use App\DTOs\VlogCategory\VlogCategoryUpdateDto;
use App\Repositories\VlogCategory\VlogCategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class VlogCategoryService implements VlogCategoryServiceInterface
{
    public function __construct
    (
        private VlogCategoryRepositoryInterface $vlogCategoryRepository ,
        private S3ServiceInterface              $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->vlogCategoryRepository->dataTable();
    }

    public function getActiveList(): mixed
    {
        return $this->vlogCategoryRepository->getActiveList();
    }

    public function find(int $id): mixed
    {
        $model = $this->vlogCategoryRepository->find($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }


    public function store(VlogCategoryStoreDto $dto): mixed
    {
        $iconPath = null;
        if ($dto->icon) {
            $iconPath = $this->s3Service->upload($dto->icon, "vlog-category");
        }
        return $this->vlogCategoryRepository->create([
            "name" => $dto->name,
            "url" => $dto->url,
            "icon" => $iconPath,
            "status" => $dto->status,
        ]);
    }

    public function update(VlogCategoryUpdateDto $dto): bool
    {
        $vlogCategory = $this->find($dto->vlogCategoryId);
        $iconPath = $vlogCategory->icon;
        if ($dto->icon) {
            $this->s3Service->remove("banner/" . $iconPath);
            $iconPath = $this->s3Service->upload($dto->icon, "vlog-category");
        }
        return $this->vlogCategoryRepository->update($vlogCategory, [
            "url" => $dto->url,
            "name" => $dto->name,
            "icon" => $iconPath,
            "status" => $dto->status,
        ]);
    }

    public function sort(VlogCategorySortDto $dto): bool
    {
        foreach ($dto->vlogs as $item) {
            $this->vlogCategoryRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
