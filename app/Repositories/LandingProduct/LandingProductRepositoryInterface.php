<?php

namespace App\Repositories\LandingProduct;

use App\Repositories\Base\BaseRepositoryInterface;

interface LandingProductRepositoryInterface extends  BaseRepositoryInterface
{
    public function getWithProduct($landingId);
}
