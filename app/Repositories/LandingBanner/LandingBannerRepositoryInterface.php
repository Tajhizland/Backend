<?php

namespace App\Repositories\LandingBanner;

use App\Repositories\Banner\BannerRepositoryInterface;
use App\Repositories\Base\BaseRepositoryInterface;

interface LandingBannerRepositoryInterface extends  BaseRepositoryInterface
{
    public function getByLandingId($landingId);
}
