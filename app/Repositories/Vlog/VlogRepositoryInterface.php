<?php

namespace App\Repositories\Vlog;


use App\Repositories\Base\BaseRepositoryInterface;

interface VlogRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function findByUrl($url);
    public function listing();
    public function getLastActives();
}
