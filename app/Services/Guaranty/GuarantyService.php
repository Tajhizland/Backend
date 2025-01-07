<?php

namespace App\Services\Guaranty;

use App\Repositories\Guaranty\GuarantyRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GuarantyService implements GuarantyServiceInterface
{
    public function __construct
    (
        private GuarantyRepositoryInterface $guarantyRepository,
        private S3ServiceInterface          $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->guarantyRepository->dataTable();
    }

    public function findByUrl($url)
    {
        $data = $this->guarantyRepository->findByUrl($url);
        if (!$data) {
            throw  new NotFoundHttpException();
        }
        return $data;
    }

    public function findById($id)
    {
        return $this->guarantyRepository->findOrFail($id);
    }

    public function store($name, $free, $description, $icon, $status, $url)
    {
        $iconPath = "";
        if ($icon) {
            $iconPath = $this->s3Service->upload($icon, "guaranty");
        }
        return $this->guarantyRepository->create([
            "name" => $name,
            "free" => $free,
            "url" => $url,
            "description" => $description,
            "icon" => $iconPath,
            "status" => $status
        ]);
    }

    public function update($id, $name, $free, $description, $icon, $status, $url)
    {
        $guaranty = $this->guarantyRepository->findOrFail($id);
        $iconPath = $guaranty->icon;
        if ($icon) {
            $this->s3Service->remove("guaranty/" . $iconPath);
            $iconPath = $this->s3Service->upload($icon, "guaranty");
        }
        return $this->guarantyRepository->update($guaranty, [
            "name" => $name,
            "free" => $free,
            "url" => $url,
            "description" => $description,
            "icon" => $iconPath,
            "status" => $status
        ]);
    }

    public function getActives()
    {
        return $this->guarantyRepository->getActives();
    }

    public function getSitemapData()
    {
        return $this->guarantyRepository->getSitemapData();
    }

    public function calculatePrice(float $price): float
    {
        if ($price < 0) {
            throw new \InvalidArgumentException('Price must be a positive number.');
        }

        if ($price <= 10000000) {
            return $price * 1.1 / 100;
        }

        if ($price <= 20000000) {
            return $price * 1 / 100;
        }

        if ($price <= 30000000) {
            return $price * 0.9 / 100;
        }

        if ($price <= 40000000) {
            return $price * 0.8 / 100;
        }

        if ($price <= 50000000) {
            return $price * 0.7 / 100;
        }

        if ($price <= 70000000) {
            return $price * 0.6 / 100;
        }

        if ($price <= 100000000) {
            return $price * 0.5 / 100;
        }

        if ($price <= 200000000) {
            return $price * 0.4 / 100;
        }

        return $price * 0.3 / 100;

    }
}
