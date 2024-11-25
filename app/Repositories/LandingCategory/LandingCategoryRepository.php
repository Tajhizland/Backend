<?php

namespace App\Repositories\LandingCategory;

use App\Models\LandingCategory;
use App\Repositories\Base\BaseRepository;

class LandingCategoryRepository extends BaseRepository implements LandingCategoryRepositoryInterface
{
    public function __construct(LandingCategory $model)
    {
        parent::__construct($model);
    }

    public function getWithCategory($landingId)
    {
        return $this->model::where("landing_id",$landingId)->with("category")->get();
    }
}
