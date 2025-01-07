<?php

namespace App\Repositories\Landing;

use App\Repositories\Base\BaseRepositoryInterface;

interface LandingRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function findByUrl($url);
    public function getSitemapData();

}
