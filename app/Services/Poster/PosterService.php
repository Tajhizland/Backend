<?php

namespace App\Services\Poster;

use App\DTOs\Poster\PosterStoreDto;
use App\DTOs\Poster\PosterUpdateDto;
use App\Repositories\Poster\PosterRepositoryInterface;
use App\Services\S3\S3ServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class PosterService implements PosterServiceInterface
{
    public function __construct(
        private PosterRepositoryInterface $posterRepository,
        private S3ServiceInterface        $s3Service,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->posterRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $poster = $this->posterRepository->find($id);
        if (!$poster) {
            throw new NotFoundHttpException();
        }
        return $poster;
    }

    public function store(PosterStoreDto $dto): mixed
    {
        return $this->posterRepository->create([
            "image" => $this->s3Service->upload($dto->image, "poster"),
        ]);
    }

    public function update(PosterUpdateDto $dto): bool
    {
        $poster = $this->find($dto->posterId);
        $this->s3Service->remove("poster/" . $poster->image);
        return $this->posterRepository->update($poster, [
            "image" => $this->s3Service->upload($dto->image, "poster"),
        ]);
    }
}
