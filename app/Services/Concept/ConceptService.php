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

    public function store($title, $description, $status, $image)
    {
        $imagePath = "";
        if ($image) {
            $imagePath = $this->s3Service->upload($image, "concept");
        }
        return $this->conceptRepository->store($title, $description, $status, $imagePath);
    }

    public function update($id, $title, $description, $status, $image)
    {
        $concept = $this->conceptRepository->findOrFail($id);
        $imagePath = $concept->image;
        if ($image) {
            $this->s3Service->remove("concept/" . $imagePath);
            $this->s3Service->upload($image, "concept");
        }
        $this->conceptRepository->updateConcept($concept, $title, $description, $status, $imagePath);
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
        $item = $this->categoryConceptRepository->findByCategoryId($conceptId , $categoryId);
        if ($item) {
            throw new BreakException(\Lang::get("exceptions.category_already_exist"));
        }
        return $this->categoryConceptRepository->store( $conceptId , $categoryId);
    }

    public function deleteItem($id)
    {
        $item = $this->categoryConceptRepository->findOrFail($id);
        return $this->categoryConceptRepository->delete($item);
    }
}
