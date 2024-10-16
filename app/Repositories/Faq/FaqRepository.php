<?php

namespace App\Repositories\Faq;

use App\Models\Faq;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    public function __construct(Faq $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Faq::class)
            ->allowedFilters(['question', 'answer', 'status', 'id', 'created_at'])
            ->allowedSorts(['question', 'answer', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function getActive()
    {
        return $this->model::active()->latest("id")->get();
    }

    public function store($question, $answer, $status)
    {
        return $this->model::create([
            "question" => $question,
            "answer" => $answer,
            "status" => $status
        ]);
    }

    public function updateFaq(Faq $faq, $question, $answer, $status)
    {
        return $faq->update([
            "question" => $question,
            "answer" => $answer,
            "status" => $status
        ]);
    }
}
