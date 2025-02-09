<?php

namespace App\Service\CategoryTree;

interface CategoryTreeServiceInterface
{
    public function getCategoryAndChildrenIds($category);

}
