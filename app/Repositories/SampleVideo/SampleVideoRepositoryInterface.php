<?php

namespace App\Repositories\SampleVideo;

use App\Repositories\Base\BaseRepositoryInterface;

interface SampleVideoRepositoryInterface extends BaseRepositoryInterface
{
    public function findByVideoId($videoId);
}
