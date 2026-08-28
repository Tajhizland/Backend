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
            ->select("id", "title", "description", "status", "icon", "created_at", "updated_at")
            ->with([
                'categories' => fn ($query) => $query
                    ->select("categories.id", "categories.name", "categories.url", "categories.image",
                        "categories.status", "categories.parent_id", "categories.description",
                        "categories.created_at", "categories.updated_at")
                    ->withPivot('display'),
            ])
            ->latest("id")
            ->get();
    }

    public function dataTable()
    {
        return QueryBuilder::for(Concept::class)
            ->allowedFilters(...['title', 'description', 'status', 'id', 'created_at'])
            ->allowedSorts(...['title', 'description', 'status', 'id', 'created_at'])
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
