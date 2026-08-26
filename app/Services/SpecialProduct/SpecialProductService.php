<?php

namespace App\Services\SpecialProduct;

use App\DTOs\SpecialProduct\SpecialProductAddDto;
use App\DTOs\SpecialProduct\SpecialProductHomepageDto;
use App\DTOs\SpecialProduct\SpecialProductSortDto;
use App\Repositories\SpecialProduct\SpecialProductRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class SpecialProductService implements SpecialProductServiceInterface
{
    public function __construct(private SpecialProductRepositoryInterface $specialProductRepository)
    {
    }

    public function dataTable(): mixed
    {
        return $this->specialProductRepository->dataTable();
    }

    public function add(SpecialProductAddDto $dto): mixed
    {
        return $this->specialProductRepository->add($dto->product_id);
    }

    public function find(int $id): mixed
    {
        $item = $this->specialProductRepository->find($id);
        if (!$item) {
            throw new NotFoundHttpException();
        }
        return $item;
    }

    public function delete(int $id): bool|null
    {
        return $this->specialProductRepository->delete($this->find($id));
    }

    public function showHomepage(SpecialProductHomepageDto $dto): bool
    {
        $item = $this->find($dto->specialProductId);
        return $this->specialProductRepository->update($item, ["homepage" => $dto->homepage]);
    }

    public function sort(SpecialProductSortDto $dto): bool
    {
        foreach ($dto->special as $item) {
            $this->specialProductRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
