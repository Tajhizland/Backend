<?php

namespace App\Services\Coupon;

use App\DTOs\Coupon\CouponStoreDto;
use App\DTOs\Coupon\CouponStoreGroupDto;
use App\DTOs\Coupon\CouponUpdateDto;

interface CouponServiceInterface
{
    public function dataTable(): mixed;

    public function generate(): string;

    public function find(int $id): mixed;

    public function check($code, $userId, $totalItemsPrice = null): mixed;

    public function store(CouponStoreDto $dto): mixed;

    public function storeGroup(CouponStoreGroupDto $dto): array;

    public function update(CouponUpdateDto $dto): bool;
}
