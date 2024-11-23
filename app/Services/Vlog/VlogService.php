<?php

namespace App\Services\Vlog;

use App\Repositories\Vlog\VlogRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class VlogService implements VlogServiceInterface
{
    public function __construct
    (
        private VlogRepositoryInterface $vlogRepository,
        private S3ServiceInterface      $s3Service,
    )
    {
    }

    public function dataTable()
    {
        return $this->vlogRepository->dataTable();
    }

    public function findById($id)
    {
        return $this->vlogRepository->findOrFail($id);
    }

    public function store($title, $description, $video, $poster, $url, $status, $categoryId)
    {
        $filePath = $this->s3Service->upload($video, "vlog");
        $posterPath = $this->s3Service->upload($poster, "vlog");
        return $this->vlogRepository->create([
            "title" => $title,
            "description" => $description,
            "video" => $filePath,
            "poster" => $posterPath,
            "status" => $status,
            "url" => $url,
            "category_id" => $categoryId,
        ]);
    }

    public function update($id, $title, $description, $video, $poster, $url, $status, $categoryId)
    {
        $vlog = $this->vlogRepository->findOrFail($id);
        $filePath = $vlog->video;
        $posterPath = $vlog->poster;
        if (isset($video)) {
            $this->s3Service->remove("vlog/" . $filePath);
            $filePath = $this->s3Service->upload($video, "vlog");
        }
        if (isset($poster)) {
            $this->s3Service->remove("vlog/" . $posterPath);
            $posterPath = $this->s3Service->upload($poster, "vlog");
        }
        $this->vlogRepository->update($vlog, [
            "title" => $title,
            "description" => $description,
            "video" => $filePath,
            "poster" => $posterPath,
            "url" => $url,
            "status" => $status,
            "category_id" => $categoryId,
        ]);
    }

    public function findByUrl($url)
    {
        return $this->vlogRepository->findByUrl($url);
    }

    public function listing($filters)
    {
        $vlogQuery = $this->vlogRepository->activeVlogQuery();
        if ($filters) {
            foreach ($filters as $filter => $value) {
                if ($filter == "category") {
                    /** Example : filter[category]=10 */
                    $vlogQuery=$this->vlogRepository->filterCategory($vlogQuery, $value);
                }
                if ($filter == "search") {
                    /** Example : filter[category]=10 */
                    $vlogQuery=$this->vlogRepository->filterTitle($vlogQuery, $value);
                }
            }
        }
        return $this->vlogRepository->paginated($vlogQuery);
    }
}
