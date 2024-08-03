<?php

namespace App\Repositories\New;

use App\Repositories\Base\BaseRepositoryInterface;

interface NewRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUrl($url);
    public function activePaginate();
}
