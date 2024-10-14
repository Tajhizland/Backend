<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CategoryConcept\CategoryConceptRequest;
use App\Http\Requests\V1\Admin\Concept\StoreConceptRequest;
use App\Http\Requests\V1\Admin\Concept\UpdateConceptRequest;
use App\Http\Requests\V1\Admin\News\NewsFileRequest;
use App\Http\Resources\V1\CategoryConcept\CategoryConceptCollection;
use App\Http\Resources\V1\Concept\ConceptCollection;
use App\Http\Resources\V1\Concept\ConceptResource;
use App\Http\Resources\V1\Filemanager\FilemanagerCollection;
use App\Services\Concept\ConceptServiceInterface;
use App\Services\FileManager\FileManagerServiceInterface;
use Illuminate\Support\Facades\Lang;

class ConceptController extends Controller
{
    public function __construct
    (
        private ConceptServiceInterface     $conceptService,
        private FileManagerServiceInterface $fileManagerService,

    )
    {
    }

    public function dataTable()
    {
        return $this->dataResponseCollection(new ConceptCollection($this->conceptService->dataTable()));
    }

    public function store(StoreConceptRequest $request)
    {
        $this->conceptService->store($request->get("title"), $request->get("description"), $request->get("status"), $request->get("image"));
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.concept")]));
    }

    public function update(UpdateConceptRequest $request)
    {
        $this->conceptService->update($request->get("id"), $request->get("title"), $request->get("description"), $request->get("status"), $request->get("image"));
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.concept")]));
    }

    public function findById($id)
    {
        return $this->dataResponse(new ConceptResource($this->conceptService->findById($id)));
    }

    public function getItems($id)
    {
        return $this->dataResponseCollection(new CategoryConceptCollection($this->conceptService->getItemsById($id)));
    }

    public function setItem(CategoryConceptRequest $request)
    {
        $this->conceptService->setItem($request->get("category_id"), $request->get("concept_id"));
        return $this->successResponse(Lang::get("action.add_to", ["attr" => Lang::get("attr.category"), "to" => Lang::get("attr.list")]));
    }

    public function deleteItem($id)
    {
        $this->conceptService->deleteItem($id);
        return $this->successResponse(Lang::get("action.remove_from", ["attr" => Lang::get("attr.category"), "from" => Lang::get("attr.list")]));
    }

}
