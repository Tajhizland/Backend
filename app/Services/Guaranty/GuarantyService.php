<?php

namespace App\Services\Guaranty;

use App\DTOs\Guaranty\GuarantyStoreDto;
use App\DTOs\Guaranty\GuarantyUpdateDto;
use App\Repositories\Guaranty\GuarantyRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class GuarantyService implements GuarantyServiceInterface
{
    public function __construct
    (
        private GuarantyRepositoryInterface $guarantyRepository,
        private S3ServiceInterface          $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->guarantyRepository->dataTable();
    }

    public function findByUrl($url): mixed
    {
        $data = $this->guarantyRepository->findByUrl($url);
        if (!$data) {
            throw  new NotFoundHttpException();
        }
        return $data;
    }

    public function find(int $id): mixed
    {
        $model = $this->guarantyRepository->find($id);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    public function store(GuarantyStoreDto $dto): mixed
    {
        $iconPath = "";
        if ($dto->icon) {
            $iconPath = $this->s3Service->upload($dto->icon, "guaranty");
        }
        return $this->guarantyRepository->create([
            "name" => $dto->name,
            "free" => $dto->free,
            "url" => $dto->url,
            "description" => $dto->description,
            "icon" => $iconPath,
            "status" => $dto->status,
        ]);
    }

    public function update(GuarantyUpdateDto $dto): bool
    {
        $guaranty = $this->find($dto->guarantyId);
        $iconPath = $guaranty->icon;
        if ($dto->icon) {
            $this->s3Service->remove("guaranty/" . $iconPath);
            $iconPath = $this->s3Service->upload($dto->icon, "guaranty");
        }
        return $this->guarantyRepository->update($guaranty, [
            "name" => $dto->name,
            "free" => $dto->free,
            "url" => $dto->url,
            "description" => $dto->description,
            "icon" => $iconPath,
            "status" => $dto->status,
        ]);
    }

    public function getActives(): mixed
    {
        return $this->guarantyRepository->getActives();
    }

    public function getSitemapData(): mixed
    {
        return $this->guarantyRepository->getSitemapData();
    }

    public function calculatePrice(float $price): float
    {
        if ($price < 0) {
            throw new \InvalidArgumentException('Price must be a positive number.');
        }

        if ($price <= 10000000) {
            return $price * 1.1 / 100 * 2;
        }

        if ($price <= 20000000) {
            return $price * 1 / 100 *2;
        }

        if ($price <= 30000000) {
            return $price * 0.9 / 100 *2;
        }

        if ($price <= 40000000) {
            return $price * 0.8 / 100 *2;
        }

        if ($price <= 50000000) {
            return $price * 0.7 / 100 *2;
        }

        if ($price <= 70000000) {
            return $price * 0.6 / 100 *2;
        }

        if ($price <= 100000000) {
            return $price * 0.5 / 100 *2;
        }

        if ($price <= 200000000) {
            return $price * 0.4 / 100 *2 ;
        }

        return $price * 0.3 / 100 *2;

    }
}
