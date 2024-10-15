<?php

namespace App\Services\ProductImage;

use App\Repositories\ProductImage\ProductImageRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class ProductImageService implements ProductImageServiceInterface
{
    public function __construct(
        private ProductImageRepositoryInterface $productImageRepository,
        private S3ServiceInterface              $s3Service
    )
    {
    }

    public function getByProductId($productId)
    {
        return $this->productImageRepository->getByProductId($productId);
    }

    public function create($productId, $image)
    {
        $imagePath = $this->s3Service->upload($image, "product");
        return $this->productImageRepository->create(["product_id" => $productId, "url" => $imagePath]);
    }

    public function remove($id)
    {
        $image = $this->productImageRepository->findOrFail($id);
        $this->s3Service->remove("product/" . $image->url);
        $this->productImageRepository->delete($image);
    }

}
