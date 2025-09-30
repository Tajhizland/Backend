<?php

namespace App\Services\SmsLog;

use App\Repositories\SmsLog\SmsLogRepositoryInterface;

class SmsLogService implements SmsLogServiceInterface
{
    public function __construct
    (
        private SmsLogRepositoryInterface $smsLogRepository,
    )
    {
    }

    public function dataTable()
    {
        return $this->smsLogRepository->dataTable();
    }

    public function find($id)
    {
        return $this->smsLogRepository->findOrFail($id);
    }

    public function update($id, $status)
    {
        $model = $this->smsLogRepository->findOrFail($id);
        $this->smsLogRepository->update($model, ["status" => $status]);
    }

    public function store($type, $status)
    {
        return $this->smsLogRepository->create([
            "type" => $type,
            "status" => $status
        ]);
    }
}
