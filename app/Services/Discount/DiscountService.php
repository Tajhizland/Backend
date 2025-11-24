<?php

namespace App\Services\Discount;

use App\Repositories\Discount\DiscountRepositoryInterface;

class DiscountService implements DiscountServiceInterface
{
    public function __construct
    (
        private DiscountRepositoryInterface $discountRepository
    )
    {

    }

    public function getByCampaignId($campaignId)
    {
        return $this->discountRepository->getByCampaignId($campaignId);
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
}
