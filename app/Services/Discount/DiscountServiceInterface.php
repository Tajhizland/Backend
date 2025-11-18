<?php

namespace App\Services\Discount;

interface DiscountServiceInterface
{
    public function getByCampaignId($campaignId);

    public function store($campaignId, $productColorId, $discount);

    public function update($id, $discount);

    public function delete($id);

}
