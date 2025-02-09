<?php

namespace App\Services\CategoryTree;

interface CategoryTreeServiceInterface
{
    public function getCategoryAndChildrenIds($category);

}
