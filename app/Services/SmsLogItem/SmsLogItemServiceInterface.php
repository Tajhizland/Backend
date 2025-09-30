<?php

namespace App\Services\SmsLogItem;

interface SmsLogItemServiceInterface
{
    public function dataTable($logId);

    public function find($id);

    public function store($smsLogId, $mobile, $message, $isSend);

}
