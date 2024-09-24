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
}
