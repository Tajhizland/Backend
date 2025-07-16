<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\RunConceptQuestion\StoreRunConceptQuestionRequest;
use App\Http\Requests\V1\Admin\RunConceptQuestion\UpdateRunConceptQuestionRequest;
use App\Http\Resources\V1\RunConceptQuestion\RunConceptQuestionCollection;
use App\Http\Resources\V1\RunConceptQuestion\RunConceptQuestionResource;
use App\Services\RunConceptQuestion\RunConceptQuestionServiceInterface;
use Illuminate\Support\Facades\Lang;

class RunConceptQuestionController extends Controller
{
    public function __construct
    (
        private RunConceptQuestionServiceInterface $conceptQuestionService
    )
    {
    }

    public function dataTable()
    {
        $response = $this->conceptQuestionService->dataTable();
        return $this->dataResponseCollection(new RunConceptQuestionCollection($response));
    }

    public function list()
    {
        $response = $this->conceptQuestionService->list();
        return $this->dataResponseCollection(new RunConceptQuestionCollection($response));
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
        return $this->successResponse(Lang::get("action.store", ["attr" => Lang::get("attr.question")]));
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
        return $this->successResponse(Lang::get("action.update", ["attr" => Lang::get("attr.question")]));
    }
}
