<?php

namespace App\Services\New;

use App\DTOs\News\NewsStoreDto;
use App\DTOs\News\NewsUpdateDto;
use App\Repositories\New\NewRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class NewService implements NewServiceInterface
{
    public function __construct(
        private NewRepositoryInterface $newRepository,
        private S3ServiceInterface     $s3Service

    )
    {
    }

    public function findByUrl($url): mixed
    {
        return $this->newRepository->findByUrl($url);
    }

    public function activePaginate($filters): mixed
    {
        $blogQuery= $this->newRepository->activePaginateQuery();
        $blogQuery=$this->renderFilter($blogQuery,$filters);
        return $this->newRepository->paginated($blogQuery);
    }
    private function renderFilter($blogQuery, $filters)
    {
        if ($filters) {
            foreach ($filters as $filter => $value) {
                if ($filter == "category") {
                    /** Example : filter[category]=10 */
                    $blogQuery = $this->newRepository->filterCategory($blogQuery, $value);
                }
            }
        }
        return $blogQuery;
    }

    public function find(int $id): mixed
    {
        $news = $this->newRepository->find($id);
        if (!$news) {
            throw new NotFoundHttpException();
        }
        return $news;
    }

    public function dataTable(): mixed
    {
        return $this->newRepository->dataTable();
    }

    public function store(NewsStoreDto $dto): mixed
    {
        $imagePath = null;
        if ($dto->image) {
            $imagePath = $this->s3Service->upload($dto->image, "blog");
        }
        return $this->newRepository->create([
            "title" => $dto->title,
            "url" => $dto->url,
            "content" => $dto->content,
            "img" => $imagePath,
            "published" => $dto->published,
            "category_id" => $dto->categoryId,
            "author" => $dto->author,
        ]);
    }

    public function update(NewsUpdateDto $dto): bool
    {
        $news = $this->find($dto->newsId);
        $imagePath = $news->img;
        if ($dto->image) {
            $this->s3Service->remove("blog/" . $imagePath);
            $imagePath = $this->s3Service->upload($dto->image, "blog");
        }
        return $this->newRepository->update($news, [
            "title" => $dto->title,
            "url" => $dto->url,
            "content" => $dto->content,
            "img" => $imagePath,
            "category_id" => $dto->categoryId,
            "published" => $dto->published,
        ]);
    }

    public function getSitemapData(): mixed
    {
        return $this->newRepository->getSitemapData();
    }

    public function getLastPost(): mixed
    {
        return $this->newRepository->getLastPost();
    }
}
