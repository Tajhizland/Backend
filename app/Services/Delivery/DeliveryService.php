<?php

namespace App\Services\Delivery;

use App\DTOs\Delivery\DeliveryStoreDto;
use App\DTOs\Delivery\DeliveryUpdateDto;
use App\Repositories\Delivery\DeliveryRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3Service;

readonly class DeliveryService implements DeliveryServiceInterface
{

    public function __construct
    (
        private DeliveryRepositoryInterface $deliveryRepository,
        private S3Service                   $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->deliveryRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $delivery = $this->deliveryRepository->find($id);
        if (!$delivery) {
            throw new NotFoundHttpException();
        }
        return $delivery;
    }

    public function store(DeliveryStoreDto $dto): mixed
    {
        $logoPath = "";
        if ($dto->logo) {
            $logoPath = $this->s3Service->upload($dto->logo, "delivery");
        }
        return $this->deliveryRepository->create([
            "name" => $dto->name,
            "status" => $dto->status,
            "description" => $dto->description,
            "price" => $dto->price,
            "logo" => $logoPath,
        ]);
    }

    public function update(DeliveryUpdateDto $dto): bool
    {
        $delivery = $this->find($dto->deliveryId);
        $logoPath = $delivery->logo;
        if ($dto->logo) {
            $this->s3Service->remove("delivery/" . $logoPath);
            $logoPath = $this->s3Service->upload($dto->logo, "delivery");
        }
        return $this->deliveryRepository->update($delivery, [
            "name" => $dto->name,
            "status" => $dto->status,
            "description" => $dto->description,
            "price" => $dto->price,
            "logo" => $logoPath,
        ]);
    }

    public function getActives(): mixed
    {
        return $this->deliveryRepository->getActiveDelivery();
    }
}
