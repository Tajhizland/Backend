<?php

namespace App\Services\Contact;

use App\DTOs\Contact\ContactStoreDto;

interface ContactServiceInterface
{
    public function dataTable(): mixed;

    public function find(int $id): mixed;

    public function remove(int $id): bool|null;

    public function store(ContactStoreDto $dto): mixed;
}
