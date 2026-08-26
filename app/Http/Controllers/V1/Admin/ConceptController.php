<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryConcept\CategoryConceptRequest;
use App\Http\Requests\Admin\Concept\SetDisplayRequest;
use App\Http\Requests\Admin\Concept\StoreConceptRequest;
use App\Http\Requests\Admin\Concept\UpdateConceptRequest;
use App\Http\Resources\Concept\ConceptResource;
use App\Services\Concept\ConceptServiceInterface;
use App\Services\FileManager\FileManagerServiceInterface;
use App\Http\Resources\CategoryConcept\CategoryConceptResource;

class ConceptController extends Controller
{
    public function __construct
    (
        private readonly ConceptServiceInterface     $conceptService,
        private readonly FileManagerServiceInterface $fileManagerService,

    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(ConceptResource::collection($this->conceptService->dataTable()));
    }

    public function store(StoreConceptRequest $request)
    {
        $this->conceptService->store($request->get("title"), $request->get("description"), $request->get("status"), $request->get("icon"));
        return $this->successResponse(__("action.store", ["attr" => __("attr.concept")]));
    }

    public function update(UpdateConceptRequest $request)
    {
        $this->conceptService->update($request->get("id"), $request->get("title"), $request->get("description"), $request->get("status"), $request->get("icon"));
        return $this->successResponse(__("action.update", ["attr" => __("attr.concept")]));
    }

    public function findById($id)
    {
        return $this->dataResponse(new ConceptResource($this->conceptService->findById($id)));
    }

    public function getItems($id)
    {
        return $this->dataResponseCollection(CategoryConceptResource::collection($this->conceptService->getItemsById($id)));
    }

    public function setItem(CategoryConceptRequest $request)
    {
        $this->conceptService->setItem($request->get("category_id"), $request->get("concept_id"));
        return $this->successResponse(__("action.add_to", ["attr" => __("attr.category"), "to" => __("attr.list")]));
    }

    public function deleteItem($id)
    {
        $this->conceptService->deleteItem($id);
        return $this->successResponse(__("action.remove_from", ["attr" => __("attr.category"), "from" => __("attr.list")]));
    }

    public function display(SetDisplayRequest $request)
    {
        $this->conceptService->setDisplay($request->get("id"), $request->get("display"));
        return $this->successResponse(__("action.submit", ["attr" => __("attr.display")]));
    }

}
