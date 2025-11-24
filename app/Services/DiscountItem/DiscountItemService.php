<?php

namespace App\Services\DiscountItem;

use App\Repositories\DiscountItem\DiscountItemRepositoryInterface;

class DiscountItemService implements DiscountItemServiceInterface
{
    public function __construct(
        private DiscountItemRepositoryInterface $discountItemRepository
    )
    {
    }

    public function getByDiscountId($discountId)
    {
        return $this->discountItemRepository->getByDiscountId($discountId);
    }
}
