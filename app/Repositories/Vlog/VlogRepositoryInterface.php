<?php

namespace App\Repositories\Vlog;


use App\Repositories\Base\BaseRepositoryInterface;

interface VlogRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function activeList();

    public function findByUrl($url);

    public function activeVlogQuery();

    public function getRelatedVlogs($category_id, $except);

    public function filterCategory($query, $categoryIds);

    public function filterTitle($query, $title);

     public function getHomePageVlogs();

     public function getLastActives();

    public function sortView($query);

    public function sortNew($query);

    public function sortOld($query);

    public function getSitemapData();

    public function getMostViewed();

    public function search($query);
    public function searchQuery($query);

    public function sort($id, $sort);

    public function getByCategory($categoryId);

}
