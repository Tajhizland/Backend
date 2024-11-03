<?php

namespace App\Repositories\Concept;

use App\Models\Concept;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class ConceptRepository extends BaseRepository implements ConceptRepositoryInterface
{
    public function __construct(Concept $model)
    {
        parent::__construct($model);
    }

    public function getActiveWithCategory()
    {
        return $this->model::active()
            ->with([
                'categories' => function ($query) {
                    $query->withPivot('display');
                }
            ])->get();
    }

    public function dataTable()
    {
        return QueryBuilder::for(Concept::class)
            ->allowedFilters(['title', 'description', 'status', 'id', 'created_at'])
            ->allowedSorts(['title', 'description', 'status', 'id', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function store($title, $description, $status, $image)
    {
        return $this->model::create([
            "image" => $image,
            "description" => $description,
            "title" => $title,
            "status" => $status
        ]);
    }

    public function updateConcept(Concept $concept, $title, $description, $status, $image)
    {
        return $concept->update([
            "image" => $image,
            "description" => $description,
            "title" => $title,
            "status" => $status
        ]);
    }
}
