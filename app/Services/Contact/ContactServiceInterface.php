<?php

namespace App\Services\Contact;

interface ContactServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function remove(int $id): bool|null;

    public function store($name, $concept, $mobile, $message, $cityId, $provinceId): mixed;
}
