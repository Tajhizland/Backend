<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Repositories\Base\BaseRepository;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function createNotification($title, $message, $link, $type)
    {
        $this->create([
            "title" => $title,
            "message" => $message,
            "seen" => 0,
            "link" => $link,
            "type" => $type
        ]);
    }

    public function seen()
    {
        $this->model::where("seen", "0")->update(["seen" => 1]);
    }

    public function dataTable()
    {
        return QueryBuilder::for(Notification::class)
            ->select("notifications.*")
            ->allowedFilters(...['title', 'seen', 'type', 'id', 'created_at', 'message', 'link'])
            ->allowedSorts(...['title', 'seen', 'type', 'id', 'created_at', 'message', 'link'])
            ->paginate($this->pageSize);
    }

    public function getUnSeen()
    {
        // Keep every unread notification alongside the ten most recent entries.
        $recentIds = $this->model::query()->latest("id")->limit(10)->pluck("id");

        return $this->model::query()
            ->where(function ($query) use ($recentIds) {
                $query->where("seen", 0)->orWhereIn("id", $recentIds);
            })
            ->latest("id")
            ->get();
    }
}
