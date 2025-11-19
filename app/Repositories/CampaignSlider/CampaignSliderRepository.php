<?php

namespace App\Repositories\CampaignSlider;

use App\Models\CampaignSlider;
use App\Repositories\Base\BaseRepository;

class CampaignSliderRepository extends BaseRepository implements CampaignSliderRepositoryInterface
{
    public function __construct(CampaignSlider $model)
    {
        parent::__construct($model);
    }

    public function getAllDesktop()
    {
        return $this->model::desktop()->orderBy("sort")->get();

    }

    public function getAllMobile()
    {
        return $this->model::mobile()->orderBy("sort")->get();
    }

    public function sort($id,$sort){
        return $this->model::where("id", $id)->update(["sort" => $sort]);

    }
}
