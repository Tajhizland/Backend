<?php

namespace App\Services\New;

use App\Repositories\New\NewRepositoryInterface;
use App\Services\Upload\S3ServiceInterface;

class NewService implements NewServiceInterface
{
    public function __construct(
        private NewRepositoryInterface $newRepository,
        private S3ServiceInterface     $s3Service

    )
    {
    }

    public function findByUrl($url)
    {
        return $this->newRepository->findByUrl($url);
    }

    public function activePaginate()
    {
        return $this->newRepository->activePaginate();
    }

    public function findById($id)
    {
        return $this->newRepository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->newRepository->dataTable();
    }

    public function storeNews($title, $url, $content, $image, $published)
    {
        $imagePath = null;
        if ($image) {
            $imagePath = $this->s3Service->upload($image, "/news/");
        }
        $this->newRepository->createNews($title, $url, $content, $imagePath, $published);
    }

    public function updateNews($id, $title, $url, $content, $image, $published)
    {
        $news=$this->newRepository->findOrFail($id);
        $imagePath = $news->img;
        if ($image) {
            $this->s3Service->remove($imagePath);
            $imagePath = $this->s3Service->upload($image, "/news/");
        }
        $this->newRepository->updateNews($id, $title, $url, $content, $imagePath, $published);
    }
}
