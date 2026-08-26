<?php

namespace App\Services\Contact;

use App\DTOs\Contact\ContactStoreDto;

use App\Repositories\Contact\ContactRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class ContactService implements ContactServiceInterface
{
    public function __construct(private ContactRepositoryInterface $contactRepository)
    {
    }

    public function dataTable(): mixed
    {
        return $this->contactRepository->dataTable();
    }

    public function store(ContactStoreDto $dto): mixed
    {
        $name = $dto->name;
        $concept = $dto->concept;
        $mobile = $dto->mobile;
        $message = $dto->message;
        $cityId = $dto->city_id;
        $provinceId = $dto->province_id;
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

    public function remove(int $id): bool|null
    {
        $contact = $this->contactRepository->findOrFail($id);
        return $this->contactRepository->delete($contact);
    }

    public function find(int $id): mixed
    {
        $contact = $this->contactRepository->find($id);
        if (!$contact) {
            throw new NotFoundHttpException();
        }
        return $contact;
    }
}
