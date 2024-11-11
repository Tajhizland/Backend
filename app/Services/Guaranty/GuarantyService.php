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
        $data=$this->guarantyRepository->findByUrl($url);
        if(!$data)
        {
            throw  new NotFoundHttpException();
        }
        return  $data;
    }
    public function findById($id)
    {
        return $this->guarantyRepository->findOrFail($id);
    }

    public function store($name, $description, $icon, $status)
    {
        $iconPath = "";
        if ($icon) {
            $iconPath = $this->s3Service->upload($icon, "guaranty");
        }
        return $this->guarantyRepository->create([
            "name" => $name,
            "description" => $description,
            "icon" => $iconPath,
            "status" => $status
        ]);
    }

    public function update($id, $name, $description, $icon, $status)
    {
        $guaranty=$this->guarantyRepository->findOrFail($id);
        $iconPath = $guaranty->icon;
        if ($icon) {
            $this->s3Service->remove("guaranty/".$iconPath);
            $iconPath = $this->s3Service->upload($icon, "guaranty");
        }
        return $this->guarantyRepository->update($guaranty,[
            "name" => $name,
            "description" => $description,
            "icon" => $iconPath,
            "status" => $status
        ]);
    }

    public function getActives()
    {
        return $this->guarantyRepository->getActives();
    }
}
