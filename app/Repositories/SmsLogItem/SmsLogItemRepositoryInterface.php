<?php

namespace App\Repositories\SmsLogItem;

use App\Repositories\Base\BaseRepositoryInterface;

interface SmsLogItemRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable($id);
}
