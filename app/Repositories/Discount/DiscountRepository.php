<?php

namespace App\Repositories\Discount;

use App\Models\Discount;
use App\Repositories\Base\BaseRepository;

class DiscountRepository extends BaseRepository implements DiscountRepositoryInterface
{
    public function __construct(Discount $model)
    {
        parent::__construct($model);
    }

    public function getByCampaignId($campaignId)
    {
        return $this->model::where("campaign_id", $campaignId)->get();
    }
}
