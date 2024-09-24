<?php

namespace App\Repositories\Concept;

use App\Models\Concept;
use App\Repositories\Base\BaseRepositoryInterface;

interface ConceptRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();

    public function store($title, $description, $status, $image);

    public function updateConcept(Concept $concept, $title, $description, $status, $image);
}
