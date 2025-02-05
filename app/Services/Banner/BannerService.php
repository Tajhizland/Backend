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

    public function create($image, $url,$type)
    {
        $imagePath = $this->s3Service->upload($image, "banner");
        return $this->bannerRepository->create([
            "image" => $imagePath,
            "type" => $type,
            "url" => $url
        ]);
    }

    public function update($id, $image, $url,$type)
    {
        $banner = $this->bannerRepository->findOrFail($id);
        $imagePath = $banner->image;
        if ($image) {
            $this->s3Service->remove("banner/" . $imagePath);
            $imagePath = $this->s3Service->upload($image, "banner");
        }
        return $this->bannerRepository->update($banner, [
            "image" => $imagePath,
            "type" => $type,
            "url" => $url
        ]);
    }

    public function findById($id)
    {
        return $this->bannerRepository->findOrFail($id);
    }
    public function getAll()
    {
        return $this->bannerRepository->all();
    }
    public function sort($array)
    {
        foreach ($array as $item) {
            $this->bannerRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }

    public function getBlogBanner()
    {
        return $this->bannerRepository->getBannerByType("blog");
    }

    public function getVlogBanner()
    {
        return $this->bannerRepository->getBannerByType("vlog");
    }

    public function getVBrandBanner()
    {
        return $this->bannerRepository->getBannerByType("brand");
    }
}
