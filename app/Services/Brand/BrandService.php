<?php

namespace App\Services\Brand;

use App\Repositories\Brand\BrandRepositoryInterface;
use App\Services\Upload\S3ServiceInterface;

class BrandService implements BrandServiceInterface
{

    public function __construct(
        private BrandRepositoryInterface $brandRepository,
        private S3ServiceInterface $s3Service
    )
    {
    }

    public function dataTable()
    {
        return $this->brandRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->brandRepository->findOrFail($id);
    }

    public function storeBrand($name, $url, $status, $image, $description)
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $this->s3Service->upload($image, "/brand/");
        }
        return $this->brandRepository->storeBrand($name, $url, $status, $imagePath, $description);
    }

    public function updateBrand($id, $name, $url, $status, $image, $description)
    {
        $brand = $this->brandRepository->findOrFail($id);
        $imagePath = $brand->image;
        if ($image) {
            $this->s3Service->remove($brand->image);
            $imagePath = $this->s3Service->upload($image, "/brand/");
        }
        return $this->brandRepository->updateBrand($brand, $name, $url, $status, $imagePath, $description);
    }
}
