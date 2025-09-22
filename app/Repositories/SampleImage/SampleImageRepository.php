<?php

namespace App\Repositories\SampleImage;

use App\Models\SampleImage;
use App\Repositories\Base\BaseRepository;

class SampleImageRepository extends BaseRepository implements SampleImageRepositoryInterface
{
    public function __construct(SampleImage $model)
    {
        parent::__construct($model);
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }
}
