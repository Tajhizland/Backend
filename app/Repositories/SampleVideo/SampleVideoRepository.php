<?php

namespace App\Repositories\SampleVideo;

use App\Models\SampleVideo;
use App\Repositories\Base\BaseRepository;

class SampleVideoRepository extends BaseRepository implements SampleVideoRepositoryInterface
{
    public function __construct(SampleVideo $model)
    {
        parent::__construct($model);
    }

    public function findByVideoId($videoId)
    {
        return $this->model::where("vlog_id",$videoId)->first();
    }

    public function getWithVlog()
    {
        return $this->model::with("vlog")->latest("id")->get();

    }
}
