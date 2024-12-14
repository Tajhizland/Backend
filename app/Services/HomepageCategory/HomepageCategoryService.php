<?php

namespace App\Services\HomepageCategory;

use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class HomepageCategoryService implements HomepageCategoryServiceInterface
{
    public function __construct(
        private HomepageCategoryRepositoryInterface $homepageCategoryRepository,
        private S3ServiceInterface                  $s3Service
    )
    {
    }

    public function dataTable()
    {
        return $this->homepageCategoryRepository->dataTable();
    }

    public function add($categoryId)
    {
        return $this->homepageCategoryRepository->add($categoryId);
    }

    public function setIcon($id, $icon)
    {
        $item = $this->homepageCategoryRepository->findOrFail($id);
        $this->s3Service->remove("homepageCategory/" .  $item->icon);
        $imagePath = $this->s3Service->upload($icon, "homepageCategory");
        return $this->homepageCategoryRepository->update($item, ["icon" => $imagePath]);
    }

    public function delete($id)
    {
        $item = $this->homepageCategoryRepository->findOrFail($id);
        return $this->homepageCategoryRepository->delete($item);
    }
}
