<?php

namespace App\Repositories\CategoryConcept;

use App\Repositories\Base\BaseRepositoryInterface;

interface CategoryConceptRepositoryInterface extends  BaseRepositoryInterface
{
    public function getByConceptId($id);
    public function findByCategoryId($conceptId , $categoryId);
    public function store($conceptId , $categoryId);
}
