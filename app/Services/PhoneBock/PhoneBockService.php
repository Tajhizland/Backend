<?php

namespace App\Services\PhoneBock;

use App\Repositories\PhoneBock\PhoneBockRepositoryInterface;

class PhoneBockService implements PhoneBockServiceInterface
{
    public function __construct
    (
        private PhoneBockRepositoryInterface $phoneBockRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->phoneBockRepository->dataTable();
    }

    public function store($name, $mobile)
    {
        return $this->phoneBockRepository->create([
            'name' => $name,
            'mobile' => $mobile
        ]);
    }

    public function update($id, $name, $mobile)
    {
        $phoneBock = $this->phoneBockRepository->findOrFail($id);
        $this->phoneBockRepository->update($phoneBock, [
            'name' => $name,
            'mobile' => $mobile
        ]);
    }

    public function find($id)
    {
        return $this->phoneBockRepository->findOrFail($id);
    }
}
