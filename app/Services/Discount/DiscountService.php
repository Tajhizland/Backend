<?php

namespace App\Services\Discount;

use App\Repositories\Discount\DiscountRepositoryInterface;
use App\Repositories\DiscountItem\DiscountItemRepositoryInterface;

class DiscountService implements DiscountServiceInterface
{
    public function __construct
    (
        private DiscountRepositoryInterface     $discountRepository,
        private DiscountItemRepositoryInterface $discountItemRepository,
    )
    {

    }

    public function store($title, $status, $start_date, $end_date)
    {
        return $this->discountRepository->create([
            "title" => $title,
            "status" => $status,
            "start_date" => $start_date,
            "end_date" => $end_date,
        ]);
    }

    public function update($id, $title, $status, $start_date, $end_date)
    {
        $discountModel = $this->discountRepository->findOrFail($id);
        return $this->discountRepository->update($discountModel,
            [
                "title" => $title,
                "status" => $status,
                "start_date" => $start_date,
                "end_date" => $end_date,
            ]
        );
    }

    public function delete($id)
    {
        $discount = $this->discountRepository->findOrFail($id);
        return $this->discountRepository->delete($discount);
    }

    public function dataTable()
    {
        return $this->discountRepository->dataTable();
    }

    public function find($id)
    {
        return $this->discountRepository->findOrFail($id);
    }

    public function getItem($id)
    {
        return $this->discountItemRepository->getByDiscountId($id);
    }

    public function setItem($discountId, $discount)
    {
        foreach ($discount as $item) {
            if ($item["discount_price"] == null || $item["discount_price"] == 0)
                continue;
            $discountItem = $this->discountItemRepository->findByProductColorId($discountId, $item["product_color_id"]);
            if ($discountItem) {
                $this->discountItemRepository->update($discountItem, ["discount" => $item["discount_price"]]);
            }
            $this->discountItemRepository->create([
                "discount_id" => $discountId,
                "product_color_id" => $item["product_color_id"],
                "discount" => $item["discount_price"]
            ]);
        }
    }

    public function deleteItem($id)
    {
        $discountItem = $this->discountItemRepository->findOrFail($id);
        return $this->discountItemRepository->delete($discountItem);
    }

    public function updateItem($discount)
    {
        foreach ($discount as $item) {
            if ($item["discount_price"] == null || $item["discount_price"] == 0)
                continue;
            $discountItem = $this->discountItemRepository->findOrFail($item["id"]);
            if ($discountItem) {
                $this->discountItemRepository->update($discountItem, ["discount" => $item["discount_price"]]);
            }
        }
    }
}
