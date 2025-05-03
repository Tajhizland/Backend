<?php

namespace App\Repositories\Vlog;


use App\Repositories\Base\BaseRepositoryInterface;

interface VlogRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();
    public function activeList();

    public function findByUrl($url);

    public function activeVlogQuery();
<<<<<<< HEAD

    public function getRelatedVlogs($category_id, $except);

    public function filterCategory($query, $categoryIds);

    public function filterTitle($query, $title);

=======
    public function getHomePageVlogs();
    public function getRelatedVlogs($category_id ,$except);
    public function filterCategory($query , $categoryIds);
    public function filterTitle($query , $title);
>>>>>>> dd498adc0bd861f90c20b3498d235604efb0fe4a
    public function getLastActives();

    public function sortView($query);

    public function sortNew($query);

    public function sortOld($query);

    public function getSitemapData();

    public function getMostViewed();

    public function search($query);

    public function sort($id, $sort);

}
