<?php

namespace App\Repositories\InstagramPost;

use App\Repositories\Base\BaseRepositoryInterface;

interface InstagramPostRepositoryInterface extends BaseRepositoryInterface
{
    public function dataTable();

    public function findWithMedia(int $id);

    public function findByUrl($url);

    public function activeQuery();

    public function filterCaption($query, $caption);

    public function paginated($query);

    public function activeList();

    public function sort($id, $sort);
}
