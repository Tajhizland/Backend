<?php

namespace App\Repositories\CategoryConcept;

use App\Models\CategoryConcept;
use App\Repositories\Base\BaseRepository;

class CategoryConceptRepository extends BaseRepository implements CategoryConceptRepositoryInterface
{
    public function __construct(CategoryConcept $model)
    {
        parent::__construct($model);
    }

    public function getByConceptId($id)
    {
        return $this->model::where("concept_id", $id)->with("category")->get();
    }

    public function findByCategoryId($conceptId, $categoryId)
    {
        return $this->model::where("category_id", $categoryId)->where("concept_id", $conceptId)->first();
    }

    public function store($conceptId, $categoryId)
    {
        return $this->model::create(["category_id" => $categoryId, "concept_id" => $conceptId]);
    }
}
