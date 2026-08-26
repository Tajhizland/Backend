<?php

namespace App\Services\HomepageCategory;

use App\DTOs\HomepageCategory\HomepageCategoryAddDto;
use App\DTOs\HomepageCategory\HomepageCategorySetIconDto;
use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class HomepageCategoryService implements HomepageCategoryServiceInterface
{
    public function __construct(
        private HomepageCategoryRepositoryInterface $homepageCategoryRepository,
        private S3ServiceInterface                  $s3Service
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->homepageCategoryRepository->dataTable();
    }

    public function add(HomepageCategoryAddDto $dto): mixed
    {
        return $this->homepageCategoryRepository->add($dto->category_id);
    }

    public function find(int $id): mixed
    {
        $item = $this->homepageCategoryRepository->find($id);
        if (!$item) {
            throw new NotFoundHttpException();
        }
        return $item;
    }

    public function setIcon(HomepageCategorySetIconDto $dto): bool
    {
        $item = $this->find($dto->homepageCategoryId);
        $this->s3Service->remove("homepageCategory/" . $item->icon);
        return $this->homepageCategoryRepository->update($item, [
            "icon" => $this->s3Service->upload($dto->icon, "homepageCategory"),
        ]);
    }

    public function delete(int $id): bool|null
    {
        return $this->homepageCategoryRepository->delete($this->find($id));
    }
}
