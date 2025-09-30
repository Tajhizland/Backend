<?php

namespace App\Services\SmsLogItem;

use App\Repositories\SmsLogItem\SmsLogItemRepositoryInterface;

class SmsLogItemService implements SmsLogItemServiceInterface
{
    public function __construct(
        private SmsLogItemRepositoryInterface $smsLogItemRepository
    )
    {
    }

    public function dataTable($logId)
    {
        return $this->smsLogItemRepository->dataTable($logId);
    }

    public function find($id)
    {
        return $this->smsLogItemRepository->findOrFail($id);
    }

    public function store($smsLogId, $mobile, $message, $isSend)
    {
        return $this->smsLogItemRepository->create(
            [
                "sms_log_id" => $smsLogId,
                "mobile" => $mobile,
                "message" => $message,
                "is_send" => $isSend
            ]
        );

    }
}
