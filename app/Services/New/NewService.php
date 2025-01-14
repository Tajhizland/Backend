<?php

namespace App\Services\New;

use App\Repositories\New\NewRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

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
            $imagePath = $this->s3Service->upload($image, "news");
        }
        $this->newRepository->create([
            "title" => $title,
            "url" => $url,
            "content" => $content,
            "image" => $imagePath,
            "published" => $published,
        ]);
    }

    public function updateNews($id, $title, $url, $content, $image, $published)
    {
        $news=$this->newRepository->findOrFail($id);
        $imagePath = $news->img;
        if ($image) {
            $this->s3Service->remove("news/".$imagePath);
            $imagePath = $this->s3Service->upload($image, "news");
        }
        $this->newRepository->update($news, [
            "title" => $title,
            "url" => $url,
            "content" => $content,
            "image" => $imagePath,
            "published" => $published,
        ]);
    }

    public function getSitemapData()
    {
        return $this->newRepository->getSitemapData();
    }
}
