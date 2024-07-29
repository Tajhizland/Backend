<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\Base\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function search($query)
    {
        return $this->model::where("name","like","%$query%")->limit(config("settings.search_item_limit"))->get();
    }
}
