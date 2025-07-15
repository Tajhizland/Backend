<?php

namespace App\Repositories\RunConceptAnswer;

use App\Models\RunConceptAnswer;
use App\Models\RunConceptQuestion;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class RunConceptAnswerRepository extends BaseRepository implements RunConceptAnswerRepositoryInterface
{
    public function __construct(RunConceptAnswer $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(RunConceptQuestion::class)
            ->allowedFilters(['run_concept_question_id', 'answer', 'status', 'price', 'id', 'created_at'])
            ->allowedSorts(['run_concept_question_id', 'answer', 'status', 'price', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function getByQuestionId($id)
    {
        return $this->model::where("run_concept_question_id", $id)->get();
    }
}
