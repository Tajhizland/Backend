<?php

namespace App\Repositories\SampleVideo;

use App\Repositories\Base\BaseRepositoryInterface;

interface SampleVideoRepositoryInterface extends BaseRepositoryInterface
{
    public function sort($id, $sort);

    public function findByVideoId($videoId);
    public function getWithVlog();
}
