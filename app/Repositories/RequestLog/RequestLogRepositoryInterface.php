<?php

namespace App\Repositories\RequestLog;

use App\Repositories\Base\BaseRepositoryInterface;

interface RequestLogRepositoryInterface extends BaseRepositoryInterface
{
    public function store(?string $title, ?string $request, ?string $response): mixed;

    public function dataTable();
}
