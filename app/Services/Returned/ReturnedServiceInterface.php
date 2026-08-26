<?php

namespace App\Services\Returned;

use App\DTOs\Returned\ReturnedStoreDto;

interface ReturnedServiceInterface
{
    public function store(ReturnedStoreDto $dto): mixed;
    public function accept($id);
    public function reject($id);
    public function dataTable();
}
