<?php

namespace App\Repositories\Gateway;

use App\Models\Gateway;
use App\Repositories\Base\BaseRepository;

class GatewayRepository extends BaseRepository implements GatewayRepositoryInterface
{
    public function __construct(Gateway $model)
    {
        parent::__construct($model);
    }
}
