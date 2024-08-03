<?php

namespace App\Repositories\New;

use App\Models\News;
use App\Repositories\Base\BaseRepository;

class NewRepository extends BaseRepository implements NewRepositoryInterface
{
    public function __construct(News $model)
    {
        parent::__construct($model);
    }
    public function findByUrl($url)
    {
        return $this->model::published()->where("url",$url)->first();
    }
    public function activePaginate()
    {
        return $this->model::published()->latest("id")->paginate($this->pageSize);
    }
}
