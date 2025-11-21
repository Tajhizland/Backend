<?php

namespace App\Repositories\Campaign;

use App\Models\Campaign;
use App\Repositories\Base\BaseRepository;
use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;

class CampaignRepository extends BaseRepository implements CampaignRepositoryInterface
{
    public function __construct(Campaign $model)
    {
        parent::__construct($model);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Campaign::class)
            ->allowedFilters(['id', 'start_date', 'end_date', 'status', 'title', 'color', 'created_at'])
            ->allowedSorts(['id', 'start_date', 'end_date', 'status', 'title', 'color', 'created_at'])
            ->paginate($this->pageSize);
    }

    public function findActiveCampaign()
    {
        return $this->model::where("status", 1)
            ->with([
                "mobileSliders",
                "desktopSliders",
                "homepageBanner",
                "homepage2Banner"
            ])
            ->where(function ($query) {
                $query->whereNull("start_date")->orWhere("start_date", "<", Carbon::now());
            })
            ->where(function ($query) {
                $query->whereNull("end_date")->orWhere("end_date", ">", Carbon::now());
            })->latest("id")->first();
    }

    public function findPendingActiveCampaign()
    {
        return $this->model::where("status", 1)
            ->where(function ($query) {
                $query->whereNull("end_date")->orWhere("end_date", ">", Carbon::now());
            })->latest("id")->first();
    }
}
