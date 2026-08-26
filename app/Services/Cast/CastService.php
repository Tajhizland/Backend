<?php

namespace App\Services\Cast;

use App\DTOs\Cast\CastStoreDto;
use App\DTOs\Cast\CastUpdateDto;
use App\Repositories\Cast\CastRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class CastService implements CastServiceInterface
{
    public function __construct
    (
        private CastRepositoryInterface $castRepository,
        private S3ServiceInterface      $s3Service
    )
    {
    }

    public function find(int $id): mixed
    {
        $cast = $this->castRepository->findWithVlog($id);
        if (!$cast) {
            throw new NotFoundHttpException();
        }
        return $cast;
    }

    public function dataTable(): mixed
    {
        return $this->castRepository->dataTable();
    }

    public function store(CastStoreDto $dto): mixed
    {
        return $this->castRepository->create([
            'title' => $dto->title,
            'image' => $this->s3Service->upload($dto->image, "cast/image"),
            'category_id' => $dto->category_id,
            'description' => $dto->description,
            'url' => $dto->url,
            'status' => $dto->status,
            'audio' => $this->s3Service->upload($dto->audio, "cast/audio"),
            'vlog_id' => $dto->vlog_id,
        ]);
    }

    public function update(CastUpdateDto $dto): bool
    {
        $cast = $this->castRepository->findOrFail($dto->castId);
        $audioPath = $cast->audio;
        $imagePath = $cast->image;
        if ($dto->audio) {
            $this->s3Service->remove("cast/audio/" . $audioPath);
            $audioPath = $this->s3Service->upload($dto->audio, "cast/audio");
        }
        if ($dto->image) {
            $this->s3Service->remove("cast/image/" . $imagePath);
            $imagePath = $this->s3Service->upload($dto->image, "cast/image");
        }
        return $this->castRepository->update($cast, [
            'title' => $dto->title,
            'description' => $dto->description,
            'url' => $dto->url,
            'category_id' => $dto->category_id,
            'status' => $dto->status,
            'audio' => $audioPath,
            'image' => $imagePath,
            'vlog_id' => $dto->vlog_id,
        ]);
    }

    public function paginated(): mixed
    {
        return $this->castRepository->paginate();
    }

    public function findByUrl($url): mixed
    {
        $response = $this->castRepository->findByUrl($url);
        $this->castRepository->update($response, ["view" => $response->view + 1]);
        return $response;
    }

    public function listing($filters): mixed
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

    public function getMostViewed(): mixed
    {
        return $this->castRepository->getMostViewed();
    }
}
