<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Repositories\Base\BaseRepository;

class NotificationRepository extends  BaseRepository implements  NotificationRepositoryInterface
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function createNotification($title , $message ,$link ,$type)
    {
        $title->create([
            "title"=>$title,
            "message"=>$message,
            "seen"=>0,
            "link"=>$link,
            "type"=>$type
        ]);
    }
    public function seen()
    {
        $this->model::update(["seen"=>1]);
    }
}
