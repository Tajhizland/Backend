<?php

namespace App\Repositories\LandingBanner;

use App\Models\LandingBanner;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class LandingBannerRepository extends BaseRepository implements LandingBannerRepositoryInterface
{
    public function __construct(LandingBanner $model)
    {
        parent::__construct($model);
    }

    public function getByLandingId($landingId)
    {
        return $this->model::where("landing_id",$landingId)->latest("id")->get();
    }
}
