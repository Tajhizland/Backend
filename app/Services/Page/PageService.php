<?php

namespace App\Services\Page;

use App\Repositories\Page\PageRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class PageService implements PageServiceInterface
{
    public function __construct
    (private PageRepositoryInterface $pageRepository,
     private S3ServiceInterface      $s3Service)
    {
    }

    public function dataTable()
    {
        return $this->pageRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->pageRepository->findOrFail($id);
    }

    public function findByUrl($url)
    {
        return $this->pageRepository->findByUrl($url);
    }

    public function store($title, $url, $image, $content, $status)
    {
        $imagePath = "";
        if ($image)
            $imagePath = $this->s3Service->upload($image, "page");
        return $this->pageRepository->store($title, $url, $imagePath, $content, $status);
    }

    public function update($id, $title, $url, $image, $content, $status)
    {
        $page = $this->pageRepository->findOrFail($id);
        $imagePath = $page->image;
        if ($image) {
            $this->s3Service->remove("page/$imagePath");
            $imagePath = $this->s3Service->upload($image, "page");
        }
        return $this->pageRepository->store($title, $url, $imagePath, $content, $status);
    }
}
