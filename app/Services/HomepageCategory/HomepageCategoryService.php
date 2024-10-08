<?php

namespace App\Services\HomepageCategory;

use App\Repositories\HomepageCategory\HomepageCategoryRepositoryInterface;

class HomepageCategoryService implements HomepageCategoryServiceInterface
{
    public function __construct(private HomepageCategoryRepositoryInterface $homepageCategoryRepository)
    {
    }

    public function dataTable()
    {
      return  $this->homepageCategoryRepository->dataTable();
    }

    public function add($categoryId)
    {
        return $this->homepageCategoryRepository->add($categoryId);
    }

    public function delete($id)
    {
        $item = $this->homepageCategoryRepository->findOrFail($id);
        return $this->homepageCategoryRepository->delete($item);
    }
}
