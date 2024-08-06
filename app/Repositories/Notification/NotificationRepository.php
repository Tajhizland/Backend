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
}
