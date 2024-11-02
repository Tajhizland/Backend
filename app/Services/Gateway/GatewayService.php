<?php

namespace App\Services\Gateway;

use App\Enums\GatewayStatus;
use App\Exceptions\BreakException;
use App\Repositories\Gateway\GatewayRepositoryInterface;

class GatewayService implements GatewayServiceInterface
{

    public function __construct
    (
        private GatewayRepositoryInterface $gatewayRepository
    )
    {
    }

    public function dataTable()
    {
        return $this->gatewayRepository->dataTable();
    }

    public function findActiveGateway()
    {
        return $this->gatewayRepository->findActiveGateway();
    }

    public function findById($id)
    {
        return $this->gatewayRepository->findOrFail($id);
    }

    public function store($name, $status, $description)
    {
        return $this->gatewayRepository->create([
            "name" => $name,
            "status" => $status,
            "description" => $description,
        ]);
    }

    public function update($id, $name, $status, $description)
    {
        $gateway = $this->gatewayRepository->findOrFail($id);
        if ($status == GatewayStatus::DeActive->value) {
            $count = $this->gatewayRepository->activeCountExceptThis($id);
            if ($count == 0) {
                throw new BreakException("یه درگاه فعال باید موجود باشد");
            }
        }
        $this->gatewayRepository->update($gateway, [
            "name" => $name,
            "status" => $status,
            "description" => $description,
        ]);
    }
}
