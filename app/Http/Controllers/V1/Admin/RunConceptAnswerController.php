<?php

namespace App\Http\Controllers\V1\Admin;

use App\DTOs\RunConceptAnswer\RunConceptAnswerStoreDto;
use App\DTOs\RunConceptAnswer\RunConceptAnswerUpdateDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RunConceptAnswer\StoreRunConceptAnswerRequest;
use App\Http\Requests\Admin\RunConceptAnswer\UpdateRunConceptAnswerRequest;
use App\Http\Resources\RunConceptAnswer\RunConceptAnswerResource;
use App\Services\RunConceptAnswer\RunConceptAnswerServiceInterface;

class RunConceptAnswerController extends Controller
{
    public function __construct
    (
        private readonly RunConceptAnswerServiceInterface $runConceptAnswerService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->runConceptAnswerService->dataTable();
        return $this->dataResponseCollection(RunConceptAnswerResource::collection($response));
    }

    public function getByQuestionId($id)
    {
        $response = $this->runConceptAnswerService->getByQuestionId($id);
        return $this->dataResponseCollection(RunConceptAnswerResource::collection($response));
    }

    public function show($id)
    {
        $response = $this->runConceptAnswerService->find($id);
        return $this->dataResponse(new RunConceptAnswerResource($response));
    }

    public function store(StoreRunConceptAnswerRequest $request)
    {
        $this->runConceptAnswerService->store(new RunConceptAnswerStoreDto(...$request->validated()));
        return $this->successResponse(__("action.store", ["attr" => __("attr.answer")]));

    }

    public function update($id, UpdateRunConceptAnswerRequest $request)
    {
        $this->runConceptAnswerService->update(new RunConceptAnswerUpdateDto($id, ...$request->validated()));
        return $this->successResponse(__("action.update", ["attr" => __("attr.answer")]));

    }
}
