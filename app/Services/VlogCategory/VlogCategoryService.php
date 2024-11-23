<?php

namespace App\Services\VlogCategory;

use App\Repositories\VlogCategory\VlogCategoryRepositoryInterface;

class VlogCategoryService implements VlogCategoryServiceInterface
{
    public function __construct
    (
        private VlogCategoryRepositoryInterface $vlogCategoryRepository
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

    public function store($name, $status)
    {
        return $this->vlogCategoryRepository->create([
            "name" => $name,
            "status" => $status
        ]);
    }

    public function update($id, $name, $status)
    {
        $vlogCategory = $this->vlogCategoryRepository->findOrFail($id);
        return $this->vlogCategoryRepository->update($vlogCategory,
            [
                "name" => $name,
                "status" => $status
            ]);
    }
}
