<?php

namespace App\Repositories\Vlog;


use App\Repositories\Base\BaseRepositoryInterface;

interface VlogRepositoryInterface extends  BaseRepositoryInterface
{
    public function dataTable();
    public function findByUrl($url);
    public function activeVlogQuery();
    public function filterCategory($query , $categoryId);
    public function filterTitle($query , $title);
    public function getLastActives();
    public function sortView($query);
    public function sortNew($query);
    public function sortOld($query);
}
