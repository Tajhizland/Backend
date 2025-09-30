<?php

namespace App\Services\SmsLog;

interface SmsLogServiceInterface
{
    public function dataTable();

    public function find($id);

    public function update($id, $status);

    public function store($type, $status);
}
