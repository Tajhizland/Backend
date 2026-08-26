<?php

namespace App\Services\TrustedBrand;

use App\DTOs\TrustedBrand\TrustedBrandStoreDto;
use App\DTOs\TrustedBrand\TrustedBrandUpdateDto;
use App\Repositories\TrustedBrand\TrustedBrandRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\S3\S3ServiceInterface;

readonly class TrustedBrandService implements TrustedBrandServiceInterface
{
    public function __construct
    (
        private TrustedBrandRepositoryInterface $trustedBrandRepository,
        private S3ServiceInterface              $s3Service
    )
    {
    }

    public function get(): mixed
    {
        return $this->trustedBrandRepository->all();
    }

    public function dataTable(): mixed
    {
        return $this->trustedBrandRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $trustedBrand = $this->trustedBrandRepository->find($id);
        if (!$trustedBrand) {
            throw new NotFoundHttpException();
        }
        return $trustedBrand;
    }

    public function delete(int $id): bool|null
    {
        return $this->trustedBrandRepository->delete($this->find($id));
    }

    public function store(TrustedBrandStoreDto $dto): mixed
    {
        return $this->trustedBrandRepository->create([
            "logo" => $this->s3Service->upload($dto->logo, "trusted-brand"),
        ]);
    }

    public function update(TrustedBrandUpdateDto $dto): bool
    {
        $trustedBrand = $this->find($dto->trustedBrandId);
        $this->s3Service->remove("trusted-brand/" . $trustedBrand->logo);
        return $this->trustedBrandRepository->update($trustedBrand, [
            "logo" => $this->s3Service->upload($dto->logo, "trusted-brand"),
        ]);
    }
}
