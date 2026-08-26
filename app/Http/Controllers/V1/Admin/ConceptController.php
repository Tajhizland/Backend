<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\Concept\ConceptSetDisplayDto;
use App\DTOs\Concept\ConceptSetItemDto;
use App\DTOs\Concept\ConceptStoreDto;
use App\DTOs\Concept\ConceptUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryConcept\CategoryConceptRequest;
use App\Http\Requests\Admin\Concept\SetDisplayRequest;
use App\Http\Requests\Admin\Concept\StoreConceptRequest;
use App\Http\Requests\Admin\Concept\UpdateConceptRequest;
use App\Http\Resources\CategoryConcept\CategoryConceptResource;
use App\Http\Resources\Concept\ConceptResource;
use App\Services\Concept\ConceptServiceInterface;

class ConceptController extends Controller
{
    public function __construct(
        private readonly ConceptServiceInterface $conceptService,
    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(ConceptResource::collection($this->conceptService->dataTable()));
    }

    public function show($id)
    {
        return $this->dataResponse(new ConceptResource($this->conceptService->find($id)));
    }

    public function store(StoreConceptRequest $request)
    {
        $this->conceptService->store(new ConceptStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.concept")]));
    }

    public function update($id, UpdateConceptRequest $request)
    {
        $this->conceptService->update(new ConceptUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.concept")]));
    }

    public function getItems($id)
    {
        return $this->dataResponseCollection(CategoryConceptResource::collection($this->conceptService->getItemsById($id)));
    }

    public function setItem(CategoryConceptRequest $request)
    {
        $this->conceptService->setItem(new ConceptSetItemDto(...$request->validated()));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.category"), "to" => __("attr.list")]));
    }

    public function deleteItem($id)
    {
        $this->conceptService->deleteItem($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.category"), "from" => __("attr.list")]));
    }

    public function setDisplay($id, SetDisplayRequest $request)
    {
        $this->conceptService->setDisplay(new ConceptSetDisplayDto($id, ...$request->validated()));
        return $this->successResponse(__("action.submit", ["attr" => __("attr.display")]));
    }
}
