<?php

namespace App\Services\Concept;

use App\Exceptions\BreakException;
use App\Repositories\CategoryConcept\CategoryConceptRepositoryInterface;
use App\DTOs\Concept\ConceptSetDisplayDto;
use App\DTOs\Concept\ConceptSetItemDto;
use App\DTOs\Concept\ConceptStoreDto;
use App\DTOs\Concept\ConceptUpdateDto;
use App\Repositories\Concept\ConceptRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class ConceptService implements ConceptServiceInterface
{
    public function __construct(
        private ConceptRepositoryInterface         $conceptRepository,
        private CategoryConceptRepositoryInterface $categoryConceptRepository,
        private S3ServiceInterface                 $s3Service
    )
    {
    }

    public function store(ConceptStoreDto $dto): mixed
    {
        $imagePath = "";
        if ($dto->icon) {
            $imagePath = $this->s3Service->upload($dto->icon, "concept");
        }
        return $this->conceptRepository->create([
            "icon" => $imagePath,
            "description" => $dto->description,
            "title" => $dto->title,
            "status" => $dto->status,
        ]);
    }

    public function update(ConceptUpdateDto $dto): bool
    {
        $concept = $this->find($dto->conceptId);
        $imagePath = $concept->icon;
        if ($dto->icon) {
            $this->s3Service->remove("concept/" . $imagePath);
            $imagePath = $this->s3Service->upload($dto->icon, "concept");
        }
        return $this->conceptRepository->update($concept, [
            "icon" => $imagePath,
            "description" => $dto->description,
            "title" => $dto->title,
            "status" => $dto->status,
        ]);
    }

    public function dataTable(): mixed
    {
        return $this->conceptRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $concept = $this->conceptRepository->find($id);
        if (!$concept) {
            throw new NotFoundHttpException();
        }
        return $concept;
    }

    public function getItemsById($id): mixed
    {
        return $this->categoryConceptRepository->getByConceptId($id);
    }

    public function setItem(ConceptSetItemDto $dto): mixed
    {
        $categoryId = $dto->category_id;
        $conceptId = $dto->concept_id;
        $item = $this->categoryConceptRepository->findByCategoryId($conceptId, $categoryId);
        if ($item) {
            throw new BreakException(\Lang::get("exceptions.category_already_exist"));
        }
        return $this->categoryConceptRepository->store($conceptId, $categoryId);
    }

    public function deleteItem(int $id): bool|null
    {
        $item = $this->categoryConceptRepository->findOrFail($id);
        return $this->categoryConceptRepository->delete($item);
    }

    public function setDisplay(ConceptSetDisplayDto $dto): bool
    {
        $categoryConcept = $this->categoryConceptRepository->findOrFail($dto->categoryConceptId);
        return $this->categoryConceptRepository->update($categoryConcept, ["display" => $dto->display]);
    }
}
