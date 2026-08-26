<?php

namespace App\Services\PhoneBock;

use App\DTOs\PhoneBock\PhoneBockStoreDto;
use App\DTOs\PhoneBock\PhoneBockUpdateDto;
use App\Repositories\PhoneBock\PhoneBockRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class PhoneBockService implements PhoneBockServiceInterface
{
    public function __construct
    (
        private PhoneBockRepositoryInterface $phoneBockRepository
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->phoneBockRepository->dataTable();
    }

    public function store(PhoneBockStoreDto $dto): mixed
    {
        return $this->phoneBockRepository->create([
            'name' => $dto->name,
            'mobile' => $dto->mobile,
        ]);
    }

    public function update(PhoneBockUpdateDto $dto): bool
    {
        $phoneBock = $this->find($dto->phoneBockId);
        return $this->phoneBockRepository->update($phoneBock, [
            'name' => $dto->name,
            'mobile' => $dto->mobile,
        ]);
    }

    public function find(int $id): mixed
    {
        $phoneBock = $this->phoneBockRepository->find($id);
        if (!$phoneBock) {
            throw new NotFoundHttpException();
        }
        return $phoneBock;
    }

    public function getAll(): mixed
    {
        return $this->phoneBockRepository->all();
    }
}
