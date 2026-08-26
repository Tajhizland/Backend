<?php

namespace App\Services\Coupon;

use App\Enums\SmsLogStatus;
use App\Jobs\GroupCouponSmsJob;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\CartItem\CartItemRepositoryInterface;
use App\DTOs\Coupon\CouponStoreDto;
use App\DTOs\Coupon\CouponStoreGroupDto;
use App\DTOs\Coupon\CouponUpdateDto;
use App\Repositories\Coupon\CouponRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\CartItem\CartItemServiceInterface;
use App\Services\SmsLog\SmsLogServiceInterface;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

readonly class CouponService implements CouponServiceInterface
{
    public function __construct
    (
        private CouponRepositoryInterface   $couponRepository,
        private CartRepositoryInterface     $cartRepository,
        private CartItemRepositoryInterface $cartItemRepository,
        private CartItemServiceInterface    $cartItemService,
        private SmsLogServiceInterface      $smsLogService,
    )
    {
    }

    public function dataTable(): mixed
    {
        return $this->couponRepository->dataTable();
    }

    public function generate(): string
    {
        $allow = false;
        while ($allow == false) {
            $code = Str::random(6);
            $exist = $this->couponRepository->findByCode($code);
            if (!$exist)
                $allow = true;
        }
        return $code;
    }

    public function find(int $id): mixed
    {
        $coupon = $this->couponRepository->find($id);
        if (!$coupon) {
            throw new NotFoundHttpException();
        }
        return $coupon;
    }

    public function store(CouponStoreDto $dto): mixed
    {
        return $this->couponRepository->create([
            "code" => $dto->code,
            "status" => $dto->status,
            "price" => $dto->price,
            "percent" => $dto->percent,
            "user_id" => $dto->user_id,
            "start_time" => $dto->start_time,
            "end_time" => $dto->end_time,
            "min_order_value" => $dto->min_order_value,
            "max_order_value" => $dto->max_order_value,
        ]);
    }

    public function update(CouponUpdateDto $dto): bool
    {
        $coupon = $this->find($dto->couponId);
        return $this->couponRepository->update($coupon, [
            "code" => $dto->code,
            "status" => $dto->status,
            "price" => $dto->price,
            "percent" => $dto->percent,
            "user_id" => $dto->user_id,
            "start_time" => $dto->start_time,
            "end_time" => $dto->end_time,
            "min_order_value" => $dto->min_order_value,
            "max_order_value" => $dto->max_order_value,
        ]);
    }

    /**
     * @param int|null $totalItemsPrice مبلغ اقلامی که کد روی آن اعمال می‌شود.
     *                                  اگر ندهی از سبد خرید فعال کاربر حساب می‌شود.
     */
    public function check($code, $userId, $totalItemsPrice = null): mixed
    {
        $coupon = $this->couponRepository->findActiveUserCode($code, $userId);
        if (!$coupon) {
            throw new  BadRequestHttpException("کد تخفیف یافت نشد");
        }
        if ($coupon->min_order_value || $coupon->max_order_value) {
            if ($totalItemsPrice === null) {
                $cart = $this->cartRepository->getCartByUserId($userId);
                $cartItems = $this->cartItemRepository->getItemsByCartId($cart->id);
                $cartItemsCalculate = $this->cartItemService->calculatePrice($cartItems);
                $totalItemsPrice = $cartItemsCalculate["totalItemPrice"];
            }

            if ($coupon->min_order_value != null && $totalItemsPrice <= $coupon->min_order_value) {
                throw new  BadRequestHttpException("برای استفاده از این کد تخفیف مجموع قیمت محصولات سبد خرید باید بالای " . $coupon->min_order_value . " تومان باشد .");
            }
            if ($coupon->max_order_value != null && $totalItemsPrice >= $coupon->max_order_value) {
                throw new  BadRequestHttpException("برای استفاده از این کد تخفیف مجموع قیمت محصولات سبد خرید باید کمتر از  " . $coupon->max_order_value . " تومان باشد .");
            }
        }
        return $coupon;


    }

    public function storeGroup(CouponStoreGroupDto $dto): array
    {
        $couponIds = [];
        foreach ($dto->userIds as $user_id) {
            $code = $this->generate();
            $coupon = $this->couponRepository->create([
                "code" => $code,
                "status" => $dto->status,
                "price" => $dto->price,
                "percent" => $dto->percent,
                "user_id" => $user_id,
                "start_time" => $dto->start_time,
                "end_time" => $dto->end_time,
                "min_order_value" => $dto->min_order_value,
                "max_order_value" => $dto->max_order_value,
            ]);
            $couponIds[] = $coupon->id;
        }

        if ($dto->send_sms && count($couponIds)) {
            $smsLog = $this->smsLogService->store("coupon", SmsLogStatus::Pending->value);
            GroupCouponSmsJob::dispatch($couponIds, $smsLog, $dto->message);
        }

        return $couponIds;
    }
}
