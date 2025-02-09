<?php

namespace App\Services\BlogCategory;

use App\Repositories\BlogCategory\BlogCategoryRepositoryInterface;

class BlogCategoryService implements BlogCategoryServiceInterface
{
    public function __construct
    (private BlogCategoryRepositoryInterface $blogCategoryRepository)
    {
    }

    public function dataTable()
    {
        return $this->blogCategoryRepository->dataTable();
    }

    public function create($name, $status, $url)
    {
        return $this->blogCategoryRepository->create(
            [
                "name" => $name,
                "status" => $status,
                "url" => $url
            ]
        );
    }

    public function update($id, $name, $status, $url)
    {
        $blogCategory = $this->blogCategoryRepository->findOrFail($id);
        return $this->blogCategoryRepository->update($blogCategory,
            [
                "name" => $name,
                "status" => $status,
                "url" => $url
            ]);
    }

    public function findById($id)
    {
        return $this->blogCategoryRepository->findOrFail($id);
    }
}
