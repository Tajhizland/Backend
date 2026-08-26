<?php

namespace App\Services\Page;

use App\DTOs\Page\PageStoreDto;
use App\DTOs\Page\PageUpdateDto;
use App\Repositories\Page\PageRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class PageService implements PageServiceInterface
{
    public function __construct
    (private PageRepositoryInterface $pageRepository,
     private S3ServiceInterface      $s3Service)
    {
    }

    public function dataTable(): mixed
    {
        return $this->pageRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $page = $this->pageRepository->find($id);
        if (!$page) {
            throw new NotFoundHttpException();
        }
        return $page;
    }

    public function findByUrl($url): mixed
    {
        return $this->pageRepository->findByUrl($url);
    }

    public function store(PageStoreDto $dto): mixed
    {
        $imagePath = "";
        if ($dto->image) {
            $imagePath = $this->s3Service->upload($dto->image, "page");
        }
        return $this->pageRepository->create([
            "title" => $dto->title,
            "url" => $dto->url,
            "content" => $dto->content,
            "image" => $imagePath,
            "status" => $dto->status,
        ]);
    }

    public function update(PageUpdateDto $dto): bool
    {
        $page = $this->find($dto->pageId);
        $imagePath = $page->image;
        if ($dto->image) {
            $this->s3Service->remove("page/$imagePath");
            $imagePath = $this->s3Service->upload($dto->image, "page");
        }
        return $this->pageRepository->update($page, [
            "title" => $dto->title,
            "url" => $dto->url,
            "content" => $dto->content,
            "image" => $imagePath,
            "status" => $dto->status,
        ]);
    }
}
