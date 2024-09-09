<?php

namespace App\Repositories\Notification;

use App\Repositories\Base\BaseRepositoryInterface;

interface NotificationRepositoryInterface extends  BaseRepositoryInterface
{
    public function createNotification($title , $message ,$link ,$type);
    public function seen();
    public function getUnSeen();
    public function dataTable();
}
