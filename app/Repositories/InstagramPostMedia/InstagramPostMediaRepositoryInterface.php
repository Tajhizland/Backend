<?php

namespace App\Repositories\InstagramPostMedia;

use App\Repositories\Base\BaseRepositoryInterface;

interface InstagramPostMediaRepositoryInterface extends BaseRepositoryInterface
{
    public function getByPost(int $postId);

    public function nextSort(int $postId): int;

    public function deleteByPost(int $postId);

    public function sort($id, $sort);
}
