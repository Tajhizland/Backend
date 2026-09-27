<?php

namespace App\Repositories\InstagramPostMedia;

use App\Models\InstagramPostMedia;
use App\Repositories\Base\BaseRepository;

class InstagramPostMediaRepository extends BaseRepository implements InstagramPostMediaRepositoryInterface
{
    public function __construct(InstagramPostMedia $model)
    {
        parent::__construct($model);
    }

    public function getByPost(int $postId)
    {
        return $this->model::where("instagram_post_id", $postId)->orderBy("sort")->orderBy("id")->get();
    }

    /** ترتیب بعدی، تا رسانه‌ی تازه به انتهای کاروسل اضافه شود نه ابتدای آن */
    public function nextSort(int $postId): int
    {
        return (int)$this->model::where("instagram_post_id", $postId)->max("sort") + 1;
    }

    public function deleteByPost(int $postId)
    {
        return $this->model::where("instagram_post_id", $postId)->delete();
    }

    public function sort($id, $sort)
    {
        return $this->model::where("id", $id)->update(["sort" => $sort]);
    }
}
