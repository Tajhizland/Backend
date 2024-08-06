<?php

namespace App\Repositories\Delivery;

use App\Models\Delivery;
use App\Repositories\Base\BaseRepository;

class DeliveryRepository extends BaseRepository implements DeliveryRepositoryInterface
{
    public function __construct(Delivery $model)
    {
        parent::__construct($model);
    }
}
