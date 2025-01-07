<?php

namespace App\Repositories\Guaranty;

use App\Repositories\Base\BaseRepositoryInterface;

interface GuarantyRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function getActives();
    public function findByUrl($url);
    public function getSitemapData();

}
