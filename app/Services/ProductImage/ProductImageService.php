<?php

namespace App\Services\ProductImage;

use App\Repositories\ProductImage\ProductImageRepositoryInterface;
use App\Services\ImageResize\ImageResizeServiceInterface;
use App\Services\S3\S3ServiceInterface;

class ProductImageService implements ProductImageServiceInterface
{
    public function __construct(
        private ProductImageRepositoryInterface $productImageRepository,
        private S3ServiceInterface              $s3Service,
        private ImageResizeServiceInterface     $imageResizeService,
    )
    {
    }

    public function getByProductId($productId)
    {
        return $this->productImageRepository->getByProductId($productId);
    }

    public function upload($productId, $images )
    {
        foreach ($images as $image) {
            $imagePath = $this->s3Service->upload($image, "product");
            var_dump($imagePath);
            die();
            $_800X = $this->imageResizeService->resize($image, 800, 800);
            $this->s3Service->upload2($_800X, "product/800", $imagePath);
            $_300X = $this->imageResizeService->resize($image, 300, 300);
            $this->s3Service->upload2($_300X, "product/300", $imagePath);
           $this->productImageRepository->create(["product_id" => $productId, "url" => $imagePath]);
        }
        return true;
    }

    public function upload2($productId, $image)
    {
        $imagePath = $this->s3Service->upload2($image, "product");

        $_800X = $this->imageResizeService->resize($image, 800, 800);
        $this->s3Service->upload2($_800X, "product/800", $imagePath);

        $_300X = $this->imageResizeService->resize($image, 300, 300);
        $this->s3Service->upload2($_300X, "product/300", $imagePath);

        return $this->productImageRepository->create(["product_id" => $productId, "url" => $imagePath]);
    }

    public function remove($id)
    {
        $image = $this->productImageRepository->findOrFail($id);
        $this->s3Service->remove("product/" . $image->url);
        $this->s3Service->remove("product/800/" . $image->url);
        $this->s3Service->remove("product/300/" . $image->url);
        $this->productImageRepository->delete($image);
    }

    public function sort($array)
    {
        foreach ($array as $item) {
            $this->productImageRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
