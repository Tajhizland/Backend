<?php

namespace App\Repositories\CampaignSlider;

use App\Models\CampaignSlider;
use App\Models\Slider;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);

    }

    public function getByCampaignId($campaignId)
    {
        return QueryBuilder::for(CampaignSlider::class)
            ->where("campaign_id", $campaignId)
            ->allowedFilters(['id', 'url', 'status', 'type', 'sort', 'title', 'created_at'])
            ->allowedSorts(['id', 'url', 'status', 'type', 'sort', 'title', 'created_at'])
            ->paginate($this->pageSize);
    }
}
