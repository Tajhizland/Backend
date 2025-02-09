<?php

namespace App\Services\CategoryTree;

class CategoryTreeService implements CategoryTreeServiceInterface
{
    public function getCategoryAndChildrenIds($category)
    {
        $childrenIds = $category->children->flatMap(function ($child) {
             return $this->getCategoryAndChildrenIds($child);
        });

        return $childrenIds->prepend($category->id)->toArray();
    }
}
