<?php

namespace App\Repositories\RunConceptQuestion;

use App\Models\RunConceptQuestion;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class RunConceptQuestionRepository extends BaseRepository implements RunConceptQuestionRepositoryInterface
{
    public function __construct(RunConceptQuestion $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(RunConceptQuestion::class)
            ->allowedFilters(['question', 'parent_question', 'parent_answer', 'status', 'level', 'id', 'created_at'])
            ->allowedSorts(['question', 'parent_question', 'parent_answer', 'status', 'level', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function list()
    {
        return $this->model::where("status",1)->get();
    }
}
