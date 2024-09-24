<?php

namespace App\Services\Concept;

interface ConceptServiceInterface
{
    public function store($title, $description, $status, $image);
    public function update($id,$title, $description, $status, $image);
    public function dataTable();
    public function findById($id);
}
