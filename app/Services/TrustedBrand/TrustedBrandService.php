<?php

namespace App\Services\TrustedBrand;

use App\Repositories\TrustedBrand\TrustedBrandRepositoryInterface;
use App\Services\S3\S3ServiceInterface;

class TrustedBrandService implements TrustedBrandServiceInterface
{
    public function __construct
    (
        private TrustedBrandRepositoryInterface $trustedBrandRepository,
        private S3ServiceInterface              $s3Service
    )
    {
    }

    public function dataTable()
    {
        return $this->trustedBrandRepository->dataTable();
    }

    public function find($id)
    {
        return $this->find($id);
    }

    public function delete($id)
    {
        $trustedBrand = $this->find($id);
        $this->trustedBrandRepository->delete($trustedBrand);
    }

    public function store($logo)
    {
        $filePath = $this->s3Service->upload($logo, "trusted-brand");
        return $this->trustedBrandRepository->create(["logo" => $filePath]);
    }

    public function update($id, $logo)
    {
        $trustedBrand = $this->trustedBrandRepository->findOrFail($id);
        $this->s3Service->remove("trusted-brand/" . $trustedBrand->logo);
        $filePath = $this->s3Service->upload($logo, "trusted-brand");
        return $this->trustedBrandRepository->update($trustedBrand, ["logo" => $filePath]);
    }
}
