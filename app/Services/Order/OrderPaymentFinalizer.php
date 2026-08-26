<?php

namespace App\Services\Order;

use App\Enums\CartStatus;
use App\Enums\OrderStatus;
use App\Events\OrderPaidEvent;
use App\Models\Order;
use App\Models\User;
use App\Repositories\Cart\CartRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\OrderItem\OrderItemRepositoryInterface;
use App\Repositories\Stock\StockRepositoryInterface;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

/**
 * تنها جایی که یک سفارش «پرداخت‌شده» اعلام می‌شود.
 *
 * قبلاً این توالی (کسر کیف پول → وضعیت Paid → کم‌کردن موجودی انبار → ثبت تراکنش →
 * تکمیل سبد → رویداد OrderPaidEvent) در PaymentService پنج بار کپی شده بود.
 */
class OrderPaymentFinalizer implements OrderPaymentFinalizerInterface
{
    public function __construct(
        private OrderRepositoryInterface       $orderRepository,
        private OrderItemRepositoryInterface   $orderItemRepository,
        private StockRepositoryInterface       $stockRepository,
        private CartRepositoryInterface        $cartRepository,
        private UserRepositoryInterface        $userRepository,
        private TransactionRepositoryInterface $transactionRepository,
    )
    {
    }

    /**
     * @param  int|float  $walletDeduction  مبلغی که از کیف پول کاربر کم می‌شود (۰ یعنی هیچ)
     * @param  mixed  $trackId  کد رهگیری درگاه؛ null یعنی پرداخت کیف‌پولی و تراکنشی ثبت نمی‌شود
     * @param  User|null  $user  اگر کاربر از قبل لود شده باشد پاس داده می‌شود تا کوئری اضافه نخورد
     */
    public function markPaid(Order $order, int|float $walletDeduction = 0, $trackId = null, ?User $user = null): void
    {
        $this->applyPayment($order, $walletDeduction, $trackId, $user);
        event(new OrderPaidEvent($order));
    }

    /**
     * همان کار markPaid ولی بدون شلیک رویداد.
     *
     * برای فراخوانی داخل DB::transaction است: رویداد OrderPaidEvent پیامک و نوتیفیکیشن
     * می‌فرستد و نباید قبل از commit شلیک شود، وگرنه با rollback پیام بی‌جا رفته است.
     */
    public function applyPayment(Order $order, int|float $walletDeduction = 0, $trackId = null, ?User $user = null): void
    {
        if ($walletDeduction > 0) {
            $user ??= $this->userRepository->findOrFail($order->user_id);
            $this->userRepository->update($user, ["wallet" => $user->wallet - $walletDeduction]);
        }

        $this->orderRepository->setStatus($order, OrderStatus::Paid->value);
        $this->decrementStock($order);

        if ($trackId !== null) {
            $this->transactionRepository->createTransaction($order->user_id, $order->id, $trackId, $order->final_price);
        }

        $this->completeCart($order);
    }

    private function decrementStock(Order $order): void
    {
        foreach ($this->orderItemRepository->getByOrderId($order->id) as $item) {
            $this->stockRepository->decrement($item->product_color_id, $item->count);
        }
    }

    private function completeCart(Order $order): void
    {
        $cart = $this->cartRepository->getCartByOrderId($order->id);
        if ($cart) {
            $this->cartRepository->changeStatus($cart, CartStatus::Completed->value);
        }
    }
}
