<?php

namespace App\Services\CastCategory;

use App\Repositories\CastCategory\CastCategoryRepositoryInterface;

class CastCategoryService implements CastCategoryServiceInterface
{

    public function __construct
    (
        private CastCategoryRepositoryInterface $castCategoryRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->castCategoryRepository->dataTable();
    }

    public function find($id)
    {
        return $this->castCategoryRepository->findOrFail($id);
    }

    public function store($name, $status)
    {
        return $this->castCategoryRepository->create(['name' => $name, 'status' => $status]);
    }

    public function update($id, $name, $status)
    {
        $castCategory = $this->castCategoryRepository->findOrFail($id);
        return $this->castCategoryRepository->update($castCategory, ['name' => $name, 'status' => $status]);
    }
}
