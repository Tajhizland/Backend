<?php

namespace App\Services\Banner;

use App\Repositories\Banner\BannerRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class BannerService implements BannerServiceInterface
{
    public function __construct
    (
        private BannerRepositoryInterface $bannerRepository,
        private S3ServiceInterface        $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->bannerRepository->dataTable();
    }

    public function delete($id)
    {
        $banner = $this->bannerRepository->findOrFail($id);
        return $this->bannerRepository->delete($banner);
    }

    public function create($image, $url)
    {
        $imagePath = $this->s3Service->upload($image, "banner");
        return $this->bannerRepository->create([
            "image" => $imagePath,
            "url" => $url
        ]);
    }

    public function update($id, $image, $url)
    {
        $banner = $this->bannerRepository->findOrFail($id);
        $imagePath = $banner->image;
        if ($image) {
            $this->s3Service->remove("banner/" . $imagePath);
            $imagePath = $this->s3Service->upload($image, "banner");
        }
        return $this->bannerRepository->update($banner, [
            "image" => $imagePath,
            "url" => $url
        ]);
    }

    public function findById($id)
    {
        return $this->bannerRepository->findOrFail($id);
    }
}
