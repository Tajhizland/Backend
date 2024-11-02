<?php

namespace App\Services\Contact;

use App\Repositories\Contact\ContactRepositoryInterface;

class ContactService implements ContactServiceInterface
{
    public function __construct(private ContactRepositoryInterface $contactRepository)
    {
    }

    public function dataTable()
    {
        return $this->contactRepository->dataTable();
    }

    public function store($name, $email, $message)
    {
        return $this->contactRepository->create(
            [
                "name" => $name,
                "email" => $email,
                "message" => $message,
            ]
        );
    }

    public function remove($id)
    {
        $contact = $this->contactRepository->findOrFail($id);
        return $this->contactRepository->delete($contact);
    }

    public function find($id)
    {
        return $this->contactRepository->findOrFail($id);
    }
}
