<?php

namespace App\Services\Cast;

use App\Repositories\Cast\CastRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class CastService implements CastServiceInterface
{
    public function __construct
    (
        private CastRepositoryInterface $castRepository,
        private S3ServiceInterface      $s3Service
    )
    {
    }

    public function find($id)
    {
        return $this->castRepository->findWithVlog($id);
    }

    public function dataTable()
    {
        return $this->castRepository->dataTable();
    }

    public function store($title, $image, $description, $url, $status, $audio, $vlog_id, $category_id)
    {
        $audioPath = $this->s3Service->upload($audio, "cast/audio");
        $imagePath = $this->s3Service->upload($image, "cast/image");
        return $this->castRepository->create([
            'title' => $title,
            'image' => $imagePath,
            'category_id' => $category_id,
            'description' => $description,
            'url' => $url,
            'status' => $status,
            'audio' => $audioPath,
            'vlog_id' => $vlog_id
        ]);
    }

    public function update($id, $title, $image, $description, $url, $status, $audio, $vlog_id, $category_id)
    {
        $cast = $this->castRepository->findOrFail($id);
        $audioPath = $cast->audio;
        $imagePath = $cast->image;
        if ($audio) {
            $this->s3Service->remove("cast/audio/" . $audioPath);
            $audioPath = $this->s3Service->upload($audio, "cast/audio");
        }
        if ($image) {
            $this->s3Service->remove("cast/image/" . $imagePath);
            $imagePath = $this->s3Service->upload($image, "cast/image");
        }
        return $this->castRepository->update($cast, [
            'title' => $title,
            'description' => $description,
            'url' => $url,
            'category_id' => $category_id,
            'status' => $status,
            'audio' => $audioPath,
            'image' => $imagePath,
            'vlog_id' => $vlog_id
        ]);
    }

    public function paginated()
    {
        return $this->castRepository->paginate();
    }

    public function findByUrl($url)
    {
        $response = $this->castRepository->findByUrl($url);
        $this->castRepository->update($response, ["view" => $response->view + 1]);
        return $response;
    }

    public function listing($filters)
    {
        $castQuery = $this->castRepository->activeQuery();
        $castQuery = $this->renderFilter($castQuery, $filters);
        return $this->castRepository->paginated($castQuery);
    }

    private function renderFilter($vlogQuery, $filters)
    {
        if ($filters) {
            foreach ($filters as $filter => $value) {
                if ($filter == "category") {
                    /** Example : filter[category]=10 */
                    $vlogQuery = $this->castRepository->filterCategory($vlogQuery, $value);
                }
                if ($filter == "sort") {
                    /** Example : filter[sort]=10 */
                    if ($value == "view")
                        $vlogQuery = $this->castRepository->sortView($vlogQuery);
                    if ($value == "new")
                        $vlogQuery = $this->castRepository->sortNew($vlogQuery);
                    if ($value == "old")
                        $vlogQuery = $this->castRepository->sortOld($vlogQuery);
                }
            }
        }
        return $vlogQuery;
    }

    public function getMostViewed()
    {
        return $this->castRepository->getMostViewed();
    }
}
