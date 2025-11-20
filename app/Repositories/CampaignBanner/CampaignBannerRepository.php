<?php

namespace App\Repositories\CampaignBanner;

use App\Models\CampaignBanner;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class CampaignBannerRepository extends BaseRepository implements CampaignBannerRepositoryInterface
{
    public function __construct(CampaignBanner $model)
    {
        parent::__construct($model);
    }


    public function dataTable($campaign_id)
    {
        return QueryBuilder::for(CampaignBanner::class)
            ->where("campaign_id", $campaign_id)
            ->allowedFilters(['url', 'id', 'type', 'created_at', 'updated_at'])
            ->allowedSorts(['url', 'id', 'type', 'created_at', 'updated_at'])
            ->paginate($this->pageSize);
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }

    public function getBannerByType($type)
    {
        return $this->model::where("type", $type)->orderBy("sort")->get();
    }
}
