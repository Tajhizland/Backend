<?php

namespace App\Repositories\LandingProduct;

use App\Models\LandingProduct;
use App\Repositories\Base\BaseRepository;

class LandingProductRepository extends BaseRepository implements LandingProductRepositoryInterface
{
    public function __construct(LandingProduct $model)
    {
        parent::__construct($model);
    }
}
