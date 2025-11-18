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

    public function store($campaignId, $productColorId, $discount)
    {
        return $this->discountRepository->create([
            "discount" => $discount,
            "campaign_id" => $campaignId,
            "product_color_id" => $productColorId,
        ]);
    }

    public function update($id, $discount)
    {
        $discountModel = $this->discountRepository->findOrFail($id);
        return $this->discountRepository->update($discountModel, ["discount" => $discount]);
    }

    public function delete($id)
    {
        $discount = $this->discountRepository->findOrFail($id);
        return $this->discountRepository->delete($discount);
    }
}
