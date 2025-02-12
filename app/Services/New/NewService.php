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

    public function activePaginate($filters)
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

    public function findById($id)
    {
        return $this->newRepository->findOrFail($id);
    }

    public function dataTable()
    {
        return $this->newRepository->dataTable();
    }

    public function storeNews($title, $url, $content, $image, $published, $categoryId, $author)
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
            "category_id" => $categoryId,
            "author" => $author,
        ]);
    }

    public function updateNews($id, $title, $url, $content, $image, $published, $categoryId)
    {
        $news = $this->newRepository->findOrFail($id);
        $imagePath = $news->img;
        if ($image) {
            $this->s3Service->remove("news/" . $imagePath);
            $imagePath = $this->s3Service->upload($image, "news");
        }
        $this->newRepository->update($news, [
            "title" => $title,
            "url" => $url,
            "content" => $content,
            "image" => $imagePath,
            "category_id" => $categoryId,
            "published" => $published,
        ]);
    }

    public function getSitemapData()
    {
        return $this->newRepository->getSitemapData();
    }

    public function getLastPost()
    {
        return $this->newRepository->getLastPost();
    }
}
