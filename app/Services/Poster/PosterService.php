<?php

namespace App\Services\Poster;

use App\Repositories\Poster\PosterRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class PosterService implements PosterServiceInterface
{
    public function __construct
    (
        private PosterRepositoryInterface $posterRepository,
        private S3ServiceInterface        $s3Service
    )
    {
    }

    public function dataTable()
    {
        return $this->posterRepository->dataTable();
    }

    public function store($image)
    {
        $imagePath=$this->s3Service->upload($image,"poster");
        return $this->posterRepository->create([
            "image"=>$imagePath
        ]);
    }

    public function findById($id)
    {
        return $this->posterRepository->findOrFail($id);
    }

    public function update($id, $image)
    {
        $poster=$this->posterRepository->findOrFail($id);
        $imagePath=$poster->image;
        $this->s3Service->remove("poster/$imagePath");
        $imagePath=$this->s3Service->upload($image,"poster");
        return $this->posterRepository->update($poster,["image"=>$imagePath]);
    }
}
