<?php

namespace App\Repositories\DirectUpload;

use App\Enums\DirectUploadStatus;
use App\Models\DirectUpload;
use App\Repositories\Base\BaseRepository;

class DirectUploadRepository extends BaseRepository implements DirectUploadRepositoryInterface
{
    public function __construct(DirectUpload $model)
    {
        parent::__construct($model);
    }

    public function findByKey(string $key): ?DirectUpload
    {
        return $this->model->where("key", $key)->first();
    }

    public function findOwned(string $key, $userId): ?DirectUpload
    {
        return $this->model
            ->where("key", $key)
            ->where("user_id", $userId)
            ->first();
    }

    public function stale(int $hours)
    {
        return $this->model
            ->whereIn("status", [
                DirectUploadStatus::Pending->value,
                DirectUploadStatus::Completed->value,
            ])
            ->where("created_at", "<", now()->subHours($hours))
            ->get();
    }
}
