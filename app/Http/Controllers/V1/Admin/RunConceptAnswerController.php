<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\RunConceptAnswer\StoreRunConceptAnswerRequest;
use App\Http\Requests\V1\Admin\RunConceptAnswer\UpdateRunConceptAnswerRequest;
use App\Http\Resources\V1\RunConceptAnswer\RunConceptAnswerCollection;
use App\Http\Resources\V1\RunConceptAnswer\RunConceptAnswerResource;
use App\Services\RunConceptAnswer\RunConceptAnswerServiceInterface;
use Illuminate\Support\Facades\Lang;

class RunConceptAnswerController extends Controller
{
    public function __construct
    (
        private RunConceptAnswerServiceInterface $runConceptAnswerService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->runConceptAnswerService->dataTable();
        return $this->dataResponseCollection(new RunConceptAnswerCollection($response));
    }

    public function getByQuestionId($id)
    {
        $response = $this->runConceptAnswerService->getByQuestionId($id);
        return $this->dataResponseCollection(new RunConceptAnswerCollection($response));
    }

    public function find($id)
    {
        $response = $this->runConceptAnswerService->find($id);
        return $this->dataResponse(new RunConceptAnswerResource($response));
    }

    public function store(StoreRunConceptAnswerRequest $request)
    {
        $this->runConceptAnswerService->store(
            $request->get("run_concept_question_id"),
            $request->get("answer"),
            $request->get("status"),
            $request->get("price"),
        );
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.answer")]));

    }

    public function update(UpdateRunConceptAnswerRequest $request)
    {
        $this->runConceptAnswerService->update(
            $request->get("id"),
            $request->get("run_concept_question_id"),
            $request->get("answer"),
            $request->get("status"),
            $request->get("price"),
        );
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.answer")]));

    }
}
