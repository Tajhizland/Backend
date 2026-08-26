<?php

namespace App\Services\Discount;

use App\DTOs\Discount\DiscountSetItemDto;
use App\DTOs\Discount\DiscountSortDto;
use App\DTOs\Discount\DiscountStoreDto;
use App\DTOs\Discount\DiscountUpdateDto;
use App\DTOs\Discount\DiscountUpdateItemDto;
use App\Repositories\Discount\DiscountRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repositories\DiscountItem\DiscountItemRepositoryInterface;

readonly class DiscountService implements DiscountServiceInterface
{
    public function __construct
    (
        private DiscountRepositoryInterface     $discountRepository,
        private DiscountItemRepositoryInterface $discountItemRepository,
    )
    {

    }

    public function store(DiscountStoreDto $dto): mixed
    {
        return $this->discountRepository->create([
            "title" => $dto->title,
            "status" => $dto->status,
            "start_date" => $dto->start_date,
            "end_date" => $dto->end_date,
        ]);
    }

    public function update(DiscountUpdateDto $dto): bool
    {
        $discount = $this->find($dto->discountId);
        return $this->discountRepository->update($discount, [
            "title" => $dto->title,
            "status" => $dto->status,
            "start_date" => $dto->start_date,
            "end_date" => $dto->end_date,
        ]);
    }

    public function delete($id)
    {
        $discount = $this->discountRepository->findOrFail($id);
        return $this->discountRepository->delete($discount);
    }

    public function dataTable(): mixed
    {
        return $this->discountRepository->dataTable();
    }

    public function find(int $id): mixed
    {
        $discount = $this->discountRepository->find($id);
        if (!$discount) {
            throw new NotFoundHttpException();
        }
        return $discount;
    }

    public function getItem($id): mixed
    {
        return $this->discountItemRepository->getByDiscountId($id);
    }
    public function getTopItem($id): mixed
    {
        return $this->discountItemRepository->getTopByDiscountId($id);
    }

    public function setItem(DiscountSetItemDto $dto): void
    {
        $discountId = $dto->discount_id;
        foreach ($dto->discount as $item) {

            $discountItem = $this->discountItemRepository->findByProductColorId($discountId, $item["product_color_id"]);
            if ($item["discount_price"] != null && $item["discount_price"] != 0) {
                if ($discountItem) {
                    $this->discountItemRepository->update($discountItem, [
                        "discount_price" => $item["discount_price"],
                        "discount_expire_time" => @$item["discount_expire_time"] ?? null,
                        "top" => @$item["top"] ?? null
                    ]);
                } else
                    $this->discountItemRepository->create([
                        "discount_id" => $discountId,
                        "product_color_id" => $item["product_color_id"],
                        "discount_price" => $item["discount_price"],
                        "discount_expire_time" => @$item["discount_expire_time"] ?? null,
                        "top" => @$item["top"] ?? null
                    ]);
            } else {
                if ($discountItem) {
                    $this->discountItemRepository->delete($discountItem);
                }
            }
        }
    }

    public function deleteItem($id): bool|null
    {
        $discountItem = $this->discountItemRepository->findOrFail($id);
        return $this->discountItemRepository->delete($discountItem);
    }

    public function updateItem(DiscountUpdateItemDto $dto): void
    {
        foreach ($dto->discount as $item) {
            if ($item["discount_price"] == null || $item["discount_price"] == 0)
                continue;
            $discountItem = $this->discountItemRepository->findOrFail($item["id"]);
            if ($discountItem) {
                $this->discountItemRepository->update($discountItem, ["discount_price" => $item["discount_price"]]);
            }
        }
    }

    public function sort(DiscountSortDto $dto): bool
    {
        foreach ($dto->discounts as $item) {
            $this->discountItemRepository->sort($item["id"], $item["sort"]);
        }
        return true;
    }
}
