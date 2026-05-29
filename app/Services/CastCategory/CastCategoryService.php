<?php

namespace App\Services\CastCategory;

use App\Repositories\CastCategory\CastCategoryRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class CastCategoryService implements CastCategoryServiceInterface
{

    public function __construct
    (
        private CastCategoryRepositoryInterface $castCategoryRepository,
        private S3ServiceInterface              $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->castCategoryRepository->dataTable();
    }

    public function get()
    {
        return $this->castCategoryRepository->getActives();
    }

    public function find($id)
    {
        return $this->castCategoryRepository->findOrFail($id);
    }

    public function store($name, $status, $icon)
    {
        $iconPath = $this->s3Service->upload($icon, "cast-category");

        return $this->castCategoryRepository->create(['name' => $name, 'status' => $status, 'icon' => $iconPath]);
    }

    public function update($id, $name, $status, $icon)
    {
        $castCategory = $this->castCategoryRepository->findOrFail($id);
        $iconPath = $castCategory->icon;
        if ($icon) {
            $this->s3Service->remove("cast-category/" . $iconPath);
            $iconPath = $this->s3Service->upload($icon, "cast-category");
        }
        return $this->castCategoryRepository->update($castCategory, ['name' => $name, 'status' => $status, 'icon' => $iconPath]);
    }

}
