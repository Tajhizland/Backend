<?php

namespace App\Services\Breadcrumb;

use App\Enums\CategoryStatus;
use App\Repositories\Category\CategoryRepositoryInterface;

class BreadcrumbService implements BreadcrumbServiceInterface
{
    public function __construct
    (
        private  CategoryRepositoryInterface $categoryRepository
    )
    {
    }

    public function generate($category)
    {
        $breadcrumb = [$category];
        while ($category->parent_id != 0) {
            $category = $this->categoryRepository->findOrFail($category->parent_id);
            if ($category && $category->status == CategoryStatus::Active ) {
                $breadcrumb[] = $category;
            }
        }
        return array_reverse($breadcrumb);
    }
}
