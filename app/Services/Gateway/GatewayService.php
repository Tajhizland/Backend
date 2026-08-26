<?php

namespace App\Services\Gateway;

use App\Enums\GatewayStatus;
use App\DTOs\Gateway\GatewayStoreDto;
use App\DTOs\Gateway\GatewayUpdateDto;
use App\Repositories\Gateway\GatewayRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class GatewayService implements GatewayServiceInterface
{

    public function __construct
    (
        private GatewayRepositoryInterface $gatewayRepository
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->gatewayRepository->dataTable();
    }

    public function findActiveGateway(): mixed
    {
        return $this->gatewayRepository->findActiveGateway();
    }

    public function find(int $id): mixed
    {
        $gateway = $this->gatewayRepository->find($id);
        if (!$gateway) {
            throw new NotFoundHttpException();
        }
        return $gateway;
    }

    public function store(GatewayStoreDto $dto): mixed
    {
        return $this->gatewayRepository->create([
            "name" => $dto->name,
            "status" => $dto->status,
            "description" => $dto->description,
        ]);
    }

    public function update(GatewayUpdateDto $dto): bool
    {
        $gateway = $this->find($dto->gatewayId);
        if ($dto->status == GatewayStatus::DeActive->value) {
            $count = $this->gatewayRepository->activeCountExceptThis($dto->gatewayId);
            if ($count == 0) {
                throw new BadRequestHttpException("یه درگاه فعال باید موجود باشد");
            }
        }
        return $this->gatewayRepository->update($gateway, [
            "name" => $dto->name,
            "status" => $dto->status,
            "description" => $dto->description,
        ]);
    }
}
