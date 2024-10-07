<?php

namespace App\Services\PopularCategory;

use App\Repositories\PopularCategory\PopularCategoryRepositoryInterface;

class PopularCategoryService implements PopularCategoryServiceInterface
{
    public function __construct(private PopularCategoryRepositoryInterface $popularCategoryRepository)
    {
    }

    public function dataTable()
    {
       return $this->popularCategoryRepository->dataTable();
    }

    public function add($categoryId)
    {
        return $this->popularCategoryRepository->add($categoryId);
    }

    public function delete($id)
    {
        $item= $this->popularCategoryRepository->findOrFail($id);
       return $this->popularCategoryRepository->delete($item);
    }
}
