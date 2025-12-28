<?php

namespace App\Repositories\Cast;

use App\Repositories\Base\BaseRepositoryInterface;

interface CastRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();

    public function findByUrl($url);

    public function findWithVlog($id);

    public function activeQuery();
    public function getMostViewed();

    public function filterCategory($query, $categoryIds);

    public function sortView($query);

    public function sortNew($query);

    public function sortOld($query);
    public function paginated($query);
}
