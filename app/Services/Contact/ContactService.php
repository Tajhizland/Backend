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

    public function store($name,$concept, $mobile, $message ,$cityId,$provinceId)
    {
        return $this->contactRepository->create(
            [
                "name" => $name,
                "mobile" => $mobile,
                "concept" => $concept,
                "message" => $message,
                "city_id" => $cityId,
                "province_id" => $provinceId,
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
