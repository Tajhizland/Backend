<?php

namespace App\Repositories\Sample;

use App\Repositories\Base\BaseRepositoryInterface;

interface SampleRepositoryInterface extends BaseRepositoryInterface
{
    public function first();
}
