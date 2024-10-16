<?php

namespace App\Repositories\Page;

use App\Models\Page;
use App\Repositories\Base\BaseRepositoryInterface;

interface PageRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function findByUrl($url);
    public function store($title , $url , $image , $content , $status);
    public function updateFaq(Page $page ,$title , $url , $image , $content , $status);
}
