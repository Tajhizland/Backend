<?php

namespace App\Services\Delivery;

use App\Repositories\Delivery\DeliveryRepositoryInterface;
use App\Services\S3\S3Service;

class DeliveryService implements DeliveryServiceInterface
{

    public function __construct
    (
        private DeliveryRepositoryInterface $deliveryRepository,
        private S3Service                   $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->deliveryRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->deliveryRepository->findOrFail($id);
    }

    public function store($name, $status, $description, $price, $logo)
    {
        $logoPath = "";
        if ($logo) {
            $logoPath = $this->s3Service->upload($logo, "delivery");
        }
        return $this->deliveryRepository->create([
            "name" => $name,
            "status" => $status,
            "description" => $description,
            "price" => $price,
            "logo" => $logoPath,
        ]);
    }

    public function update($id, $name, $status, $description, $price, $logo)
    {
        $delivery = $this->deliveryRepository->findOrFail($id);
        $logoPath = $delivery->logo;
        if ($logo) {
            $this->s3Service->remove("delivery/" . $logoPath);
            $logoPath = $this->s3Service->upload($logo, "delivery");
        }
        return $this->deliveryRepository->update($delivery,
            [
                "name" => $name,
                "status" => $status,
                "description" => $description,
                "price" => $price,
                "logo" => $logoPath,
            ]);
    }

    public function getActives()
    {
        return $this->deliveryRepository->getActiveDelivery();
    }
}
