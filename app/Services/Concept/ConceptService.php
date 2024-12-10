<?php

namespace App\Services\Concept;

use App\Exceptions\BreakException;
use App\Repositories\CategoryConcept\CategoryConceptRepositoryInterface;
use App\Repositories\Concept\ConceptRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class ConceptService implements ConceptServiceInterface
{
    public function __construct(
        private ConceptRepositoryInterface         $conceptRepository,
        private CategoryConceptRepositoryInterface $categoryConceptRepository,
        private S3ServiceInterface                 $s3Service
    )
    {
    }

    public function store($title, $description, $status, $icon)
    {
        $imagePath = "";
        if ($icon) {
            $imagePath = $this->s3Service->upload($icon, "concept");
        }
        return $this->conceptRepository->create(
            [
                "icon" => $imagePath,
                "description" => $description,
                "title" => $title,
                "status" => $status
            ]
        );
    }

    public function update($id, $title, $description, $status, $icon)
    {
        $concept = $this->conceptRepository->findOrFail($id);
        $imagePath = $concept->icon;
        if ($icon) {
            $this->s3Service->remove("concept/" . $imagePath);
            $imagePath = $this->s3Service->upload($icon, "concept");
        }
        $this->conceptRepository->update($concept,
            [
                "icon" => $imagePath,
                "description" => $description,
                "title" => $title,
                "status" => $status
            ]);
    }

    public function dataTable()
    {
        return $this->conceptRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->conceptRepository->findOrFail($id);
    }

    public function getItemsById($id)
    {
        return $this->categoryConceptRepository->getByConceptId($id);
    }

    public function setItem($categoryId, $conceptId)
    {
        $item = $this->categoryConceptRepository->findByCategoryId($conceptId, $categoryId);
        if ($item) {
            throw new BreakException(\Lang::get("exceptions.category_already_exist"));
        }
        return $this->categoryConceptRepository->store($conceptId, $categoryId);
    }

    public function deleteItem($id)
    {
        $item = $this->categoryConceptRepository->findOrFail($id);
        return $this->categoryConceptRepository->delete($item);
    }

    public function setDisplay($id, $display)
    {
        $categoryConcept = $this->categoryConceptRepository->findOrFail($id);
        return $this->categoryConceptRepository->update($categoryConcept, ["display" => $display]);
    }
}
