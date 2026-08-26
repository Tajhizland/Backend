<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\RunConceptQuestion\RunConceptQuestionStoreDto;
use App\DTOs\RunConceptQuestion\RunConceptQuestionUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RunConceptQuestion\StoreRunConceptQuestionRequest;
use App\Http\Requests\Admin\RunConceptQuestion\UpdateRunConceptQuestionRequest;
use App\Http\Resources\RunConceptQuestion\RunConceptQuestionResource;
use App\Services\RunConceptQuestion\RunConceptQuestionServiceInterface;

class RunConceptQuestionController extends Controller
{
    public function __construct
    (
        private readonly RunConceptQuestionServiceInterface $conceptQuestionService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->conceptQuestionService->dataTable();
        return $this->dataResponseCollection(RunConceptQuestionResource::collection($response));
    }

    public function list()
    {
        $response = $this->conceptQuestionService->list();
        return $this->dataResponseCollection(RunConceptQuestionResource::collection($response));
    }

    public function show($id)
    {
        $response = $this->conceptQuestionService->find($id);
        return $this->dataResponse(new RunConceptQuestionResource($response));
    }

    public function store(StoreRunConceptQuestionRequest $request)
    {
        $this->conceptQuestionService->store(new RunConceptQuestionStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.question")]));
    }

    public function update($id, UpdateRunConceptQuestionRequest $request)
    {
        $this->conceptQuestionService->update(new RunConceptQuestionUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.question")]));
    }
}
