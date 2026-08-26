<?php

namespace App\Http\Controllers\V1\Admin;

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

    public function find($id)
    {
        $response = $this->conceptQuestionService->find($id);
        return $this->dataResponse(new RunConceptQuestionResource($response));
    }

    public function store(StoreRunConceptQuestionRequest $request)
    {
        $this->conceptQuestionService->store(
            $request->get("question"),
            $request->get("status"),
            $request->get("level"),
            $request->get("parent_question"),
            $request->get("parent_answer"),
        );
        return $this->successResponse(__("action.store", ["attr" => __("attr.question")]));
    }

    public function update(UpdateRunConceptQuestionRequest $request)
    {
        $this->conceptQuestionService->update(
            $request->get("id"),
            $request->get("question"),
            $request->get("status"),
            $request->get("level"),
            $request->get("parent_question"),
            $request->get("parent_answer"),
        );
        return $this->successResponse(__("action.update", ["attr" => __("attr.question")]));
    }
}
