<?php

namespace App\Services\VlogCategory;

use App\Repositories\VlogCategory\VlogCategoryRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class VlogCategoryService implements VlogCategoryServiceInterface
{
    public function __construct
    (
        private VlogCategoryRepositoryInterface $vlogCategoryRepository ,
        private S3ServiceInterface              $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->vlogCategoryRepository->dataTable();
    }

    public function getActiveList()
    {
        return $this->vlogCategoryRepository->getActiveList();
    }

    public function findById($id)
    {
        return $this->vlogCategoryRepository->findOrFail($id);
    }


    public function store($name, $status, $url, $icon)
    {
        $iconPath = null;
        if ($icon) {
            $iconPath = $this->s3Service->upload($icon, "vlog-category");
        }
        return $this->vlogCategoryRepository->create([
            "name" => $name,
            "url" => $url,
            "icon" => $iconPath,
            "status" => $status
        ]);
    }

    public function update($id, $name, $status, $url, $icon)
    {
        $vlogCategory = $this->vlogCategoryRepository->findOrFail($id);
        $iconPath = $vlogCategory->icon;
        if ($icon) {
            $this->s3Service->remove("banner/" . $iconPath);
            $iconPath = $this->s3Service->upload($icon, "vlog-category");
        }
        return $this->vlogCategoryRepository->update($vlogCategory,
            [
                "url" => $url,
                "name" => $name,
                "icon" => $iconPath,
                "status" => $status
            ]);
    }

    public function sort($vlogs)
    {
        foreach ($vlogs as $item) {
            $this->vlogCategoryRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
