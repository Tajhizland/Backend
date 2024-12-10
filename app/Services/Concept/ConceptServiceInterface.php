<?php

namespace App\Services\Concept;

interface ConceptServiceInterface
{
    public function store($title, $description, $status, $icon);
    public function update($id,$title, $description, $status, $icon);
    public function dataTable();
    public function findById($id);
    public function getItemsById($id);
    public function deleteItem($id);
    public function setItem($categoryId , $conceptId);
    public function setDisplay($id , $display);
}
